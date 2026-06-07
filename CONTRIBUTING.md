# Contributing

Thanks for taking a look at Sub-Repository.

This project intentionally keeps a simple PHP/XAMPP style. Improvements are welcome when they preserve the core flow:

- Search subtitles.
- Upload `.srt` files.
- Download subtitles.
- Keep setup easy for a local Apache + MySQL/MariaDB environment.

## Before opening a pull request

- Run `php -l` on changed PHP files.
- Avoid committing uploaded subtitle files from `srt/`.
- Keep credentials and local URLs out of commits.
- Prefer small, focused changes over rewrites.

## Good first improvements

- Accessibility fixes.
- UI cleanup without changing the basic layout.
- Documentation improvements.
- Safer file handling.
- Tests for search, upload and download behavior.
