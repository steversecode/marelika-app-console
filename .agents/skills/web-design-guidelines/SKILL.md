---
name: web-design-guidelines
description: Review UI code for Web Interface Guidelines compliance. Use when asked to "review my UI", "check accessibility", "audit design", "review UX", or "check my site against best practices".
metadata:
  author: vercel
  version: "1.0.0"
  argument-hint: <file-or-pattern>
---

# Web Interface Guidelines

Review files for compliance with Web Interface Guidelines.

## How It Works

1. Read guidelines from [references/web-interface-guidelines.md](./references/web-interface-guidelines.md) (or fetch latest from `https://raw.githubusercontent.com/vercel-labs/web-interface-guidelines/main/command.md` when network is available).
2. Read the specified files (or prompt user for files/pattern).
3. Check against all rules in the guidelines.
4. Output findings in the terse `file:line` format.

## Guidelines Reference

- Local reference: [references/web-interface-guidelines.md](./references/web-interface-guidelines.md)
- Online upstream: `https://raw.githubusercontent.com/vercel-labs/web-interface-guidelines/main/command.md`

## Rules Overview

The guidelines enforce best practices across:
- **Accessibility**: ARIA labels on icon buttons/form controls, keyboard handlers, semantic HTML, hierarchical headings, media controls.
- **Focus States**: Visible `:focus-visible:ring-*`, never `outline: none` without focus replacement, group focus `:focus-within`.
- **Forms**: Autocomplete, proper input types/inputmode, paste never blocked, clickable labels, error states next to fields.
- **Animation**: Respect `prefers-reduced-motion`, hardware accelerated properties (`transform`, `opacity`), snappy transitions.
- **Layout & Typography**: Responsive breakpoints, fluid typography, proper line heights, content hierarchy, touch targets >= 44x44px.

## Usage

When a user provides a file or pattern argument:
1. Load guidelines from `references/web-interface-guidelines.md`
2. Read the specified files
3. Apply all rules from the guidelines
4. Output findings using the format specified in the guidelines

If no files specified, ask the user which files to review.

