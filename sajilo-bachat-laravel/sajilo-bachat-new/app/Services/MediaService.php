<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Handles uploads. Images are re-encoded with GD: this strips EXIF metadata
 * (incl. GPS), fixes phone-camera rotation and caps the dimensions, which keeps
 * quality while shrinking the file size.
 */
class MediaService
{
    public const LIBRARY_DIR = 'library';

    public static function storeImage(UploadedFile $file, string $dir, int $maxWidth = 1600, string $disk = 'public'): string
    {
        $mime = $file->getMimeType();
        $ext = match ($mime) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };
        $dir = self::normalisePath($dir);
        $name = $dir.'/'.Str::random(40).'.'.$ext;
        File::ensureDirectoryExists(public_path($dir));

        $image = self::optimise($file->getRealPath(), $mime, $maxWidth);

        if ($image === null) {
            $file->move(public_path($dir), basename($name));

            return $name;
        }

        ob_start();
        match ($ext) {
            'png' => imagepng($image, null, 7),
            'webp' => imagewebp($image, null, 85),
            default => imagejpeg($image, null, 86),
        };
        $bytes = ob_get_clean();
        imagedestroy($image);
        File::put(public_path($name), $bytes);

        return $name;
    }

    public static function storeFile(UploadedFile $file, string $dir, string $disk = 'public'): string
    {
        $dir = self::normalisePath($dir);
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'bin');
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'file';
        $path = $dir.'/'.$name.'-'.Str::random(8).'.'.$ext;
        File::ensureDirectoryExists(public_path($dir));
        $file->move(public_path($dir), basename($path));

        return $path;
    }

    /** Delete a public file, never touching the shared supplied-portrait library. */
    public static function delete(?string $path, string $disk = 'public'): void
    {
        if (! $path) {
            return;
        }

        $path = self::normalisePath($path);

        if (str_contains($path, '/'.self::LIBRARY_DIR.'/') || str_contains($path, '..')) {
            return;
        }

        $absolute = public_path($path);
        if (File::isFile($absolute)) {
            File::delete($absolute);
        }
    }

    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return asset(self::normalisePath($path));
    }

    /** Files in a library folder, e.g. the supplied committee portraits. */
    public static function library(string $dir): array
    {
        $folder = self::normalisePath($dir).'/'.self::LIBRARY_DIR;
        $absolute = public_path($folder);

        if (! File::isDirectory($absolute)) {
            return [];
        }

        return collect(File::files($absolute))
            ->map(fn ($file) => str_replace('\\', '/', $folder.'/'.$file->getFilename()))
            ->sort(SORT_NATURAL)
            ->values()
            ->all();
    }

    public static function absolutePath(?string $path): ?string
    {
        return $path ? public_path(self::normalisePath($path)) : null;
    }

    private static function normalisePath(?string $path): string
    {
        $path = trim((string) $path, '/\\');
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#^(?:storage/public|storage|public)/#', '', $path) ?: '';

        if ($path === '' || str_contains($path, '..')) {
            throw new \InvalidArgumentException('Invalid public file path.');
        }

        return $path;
    }

    private static function optimise(string $path, ?string $mime, int $maxWidth)
    {
        if (! function_exists('imagecreatetruecolor')) {
            return null;
        }

        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };

        if (! $src) {
            return null;
        }

        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            $exif = @exif_read_data($path);
            $orientation = $exif['Orientation'] ?? 1;
            $src = match ($orientation) {
                3 => imagerotate($src, 180, 0),
                6 => imagerotate($src, -90, 0),
                8 => imagerotate($src, 90, 0),
                default => $src,
            };
        }

        $w = imagesx($src);
        $h = imagesy($src);

        if ($w > $maxWidth) {
            $newH = (int) round($h * ($maxWidth / $w));
            $dst = imagecreatetruecolor($maxWidth, $newH);
            if (in_array($mime, ['image/png', 'image/webp'], true)) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $newH, $w, $h);
            imagedestroy($src);

            return $dst;
        }

        if (in_array($mime, ['image/png', 'image/webp'], true)) {
            imagealphablending($src, false);
            imagesavealpha($src, true);
        }

        return $src;
    }
}
