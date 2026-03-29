# Tailwind Light Template

Modern glassmorphism UI template built with Tailwind CSS.

**Status:** In development. Views not yet implemented will fall back to the default root views automatically.

## Design

- Frosted glass effects with backdrop blur
- Gradient backgrounds
- Rounded corners and soft shadows
- Translucent panels
- Modern typography with Inter font
- Tailwind CSS utility classes

## How Fallback Works

The ServiceProvider loads this template directory first. For any view file not found here, Laravel automatically falls back to the root `resources/views/` directory which contains the classic Bootstrap 5 template.

This means the template can be built incrementally - each new blade file added here overrides the classic version.
