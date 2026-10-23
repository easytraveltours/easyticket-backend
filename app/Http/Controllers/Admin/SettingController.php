<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Get all site settings.
     */
    public function index(): JsonResponse
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();

        $logoPath = SiteSetting::get('logo_path');
        $settings['logo_url'] = ($logoPath && Storage::disk('public')->exists($logoPath))
            ? Storage::disk('public')->url($logoPath)
            : null;

        $heroImagePath = SiteSetting::get('hero_image_path');
        $settings['hero_image_url'] = ($heroImagePath && Storage::disk('public')->exists($heroImagePath))
            ? Storage::disk('public')->url($heroImagePath)
            : null;

        $aboutImagePath = SiteSetting::get('about_image_path');
        $settings['about_image_url'] = ($aboutImagePath && Storage::disk('public')->exists($aboutImagePath))
            ? Storage::disk('public')->url($aboutImagePath)
            : null;

        $aboutExpImagePath = SiteSetting::get('about_exp_image_path');
        $settings['about_exp_image_url'] = ($aboutExpImagePath && Storage::disk('public')->exists($aboutExpImagePath))
            ? Storage::disk('public')->url($aboutExpImagePath)
            : null;

        return response()->json([
            'data' => $settings,
        ]);
    }

    /**
     * Update site settings.
     */
    public function update(Request $request): JsonResponse
    {
        $admin = $request->user();
        $settings = $request->all();

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value]
            );
        }

        ActivityLogService::log(
            $admin,
            'SETTINGS_UPDATED',
            'SETTING',
            'site_settings',
            'Updated platform system settings'
        );

        return response()->json([
            'message' => 'Settings updated successfully.',
            'data' => SiteSetting::all()->pluck('value', 'key'),
        ]);
    }

    /**
     * Upload hero background image.
     */
    public function uploadHeroImage(Request $request): JsonResponse
    {
        $request->validate([
            'hero_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $admin = $request->user();

        // Delete old hero image if exists
        $oldPath = SiteSetting::get('hero_image_path');
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        // Store new image
        $path = $request->file('hero_image')->store('hero', 'public');

        // Save the relative path in settings
        SiteSetting::set('hero_image_path', $path, 'Hero section background image path');

        ActivityLogService::log(
            $admin,
            'HERO_IMAGE_UPDATED',
            'SETTING',
            'hero_image',
            'Updated hero section background image'
        );

        return response()->json([
            'message' => 'Hero background image updated successfully.',
            'data' => [
                'hero_image_url' => Storage::disk('public')->url($path),
            ],
        ]);
    }

    /**
     * Delete hero background image.
     */
    public function deleteHeroImage(Request $request): JsonResponse
    {
        $admin = $request->user();

        $path = SiteSetting::get('hero_image_path');
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // Remove from settings
        SiteSetting::where('key', 'hero_image_path')->delete();

        ActivityLogService::log(
            $admin,
            'HERO_IMAGE_DELETED',
            'SETTING',
            'hero_image',
            'Removed hero section background image'
        );

        return response()->json([
            'message' => 'Hero background image removed successfully.',
        ]);
    }

    /**
     * Upload official website logo.
     */
    public function uploadLogo(Request $request): JsonResponse
    {
        $request->validate([
            'logo' => 'required|file|mimes:jpg,jpeg,png,webp,svg|max:4096',
        ]);

        $admin = $request->user();

        // Delete old logo if exists
        $oldPath = SiteSetting::get('logo_path');
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        // Store new logo
        $path = $request->file('logo')->store('logos', 'public');

        // Save relative path in settings
        SiteSetting::set('logo_path', $path, 'Website official logo path');

        ActivityLogService::log(
            $admin,
            'LOGO_UPDATED',
            'SETTING',
            'site_logo',
            'Updated website official logo'
        );

        return response()->json([
            'message' => 'Website logo updated successfully.',
            'data' => [
                'logo_url' => Storage::disk('public')->url($path),
            ],
        ]);
    }

    /**
     * Delete / Reset website logo to default.
     */
    public function deleteLogo(Request $request): JsonResponse
    {
        $admin = $request->user();

        $path = SiteSetting::get('logo_path');
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // Remove from settings
        SiteSetting::where('key', 'logo_path')->delete();

        ActivityLogService::log(
            $admin,
            'LOGO_DELETED',
            'SETTING',
            'site_logo',
            'Reset website logo to default'
        );

        return response()->json([
            'message' => 'Website logo removed and reset to default.',
        ]);
    }

    /**
     * Upload About Us main section image (Flight Attendant).
     */
    public function uploadAboutImage(Request $request): JsonResponse
    {
        $request->validate([
            'about_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $admin = $request->user();

        // Delete old image if exists
        $oldPath = SiteSetting::get('about_image_path');
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        // Store new image
        $path = $request->file('about_image')->store('about', 'public');

        SiteSetting::set('about_image_path', $path, 'About Us section main circular photo path');

        ActivityLogService::log(
            $admin,
            'ABOUT_IMAGE_UPDATED',
            'SETTING',
            'about_image',
            'Updated About Us section main image'
        );

        return response()->json([
            'message' => 'About Us main image updated successfully.',
            'data' => [
                'about_image_url' => Storage::disk('public')->url($path),
            ],
        ]);
    }

    /**
     * Delete / Reset About Us main image.
     */
    public function deleteAboutImage(Request $request): JsonResponse
    {
        $admin = $request->user();

        $path = SiteSetting::get('about_image_path');
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        SiteSetting::where('key', 'about_image_path')->delete();

        ActivityLogService::log(
            $admin,
            'ABOUT_IMAGE_DELETED',
            'SETTING',
            'about_image',
            'Reset About Us section main image to default'
        );

        return response()->json([
            'message' => 'About Us main image removed and reset to default.',
        ]);
    }

    /**
     * Upload About Us experience card graphic (Destinations Globe).
     */
    public function uploadAboutExpImage(Request $request): JsonResponse
    {
        $request->validate([
            'about_exp_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $admin = $request->user();

        $oldPath = SiteSetting::get('about_exp_image_path');
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $path = $request->file('about_exp_image')->store('about', 'public');

        SiteSetting::set('about_exp_image_path', $path, 'About Us experience card graphic path');

        ActivityLogService::log(
            $admin,
            'ABOUT_EXP_IMAGE_UPDATED',
            'SETTING',
            'about_exp_image',
            'Updated About Us experience card graphic'
        );

        return response()->json([
            'message' => 'Experience card graphic updated successfully.',
            'data' => [
                'about_exp_image_url' => Storage::disk('public')->url($path),
            ],
        ]);
    }

    /**
     * Delete / Reset About Us experience card graphic.
     */
    public function deleteAboutExpImage(Request $request): JsonResponse
    {
        $admin = $request->user();

        $path = SiteSetting::get('about_exp_image_path');
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        SiteSetting::where('key', 'about_exp_image_path')->delete();

        ActivityLogService::log(
            $admin,
            'ABOUT_EXP_IMAGE_DELETED',
            'SETTING',
            'about_exp_image',
            'Reset About Us experience card graphic to default'
        );

        return response()->json([
            'message' => 'Experience card graphic removed and reset to default.',
        ]);
    }
}


