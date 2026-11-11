---
name: awesome-design-md
description: >-
  Curated collection of 84+ production DESIGN.md specifications and design system tokens extracted from
  leading tech brands and websites (e.g. Apple, Stripe, Linear, Vercel, Supabase, Claude, Airbnb, Figma,
  Warp, Nike, PostHog, Replicate, Raycast, Resend, Tesla, etc.). Use this skill whenever you need to design,
  style, or generate UI matching a specific brand aesthetic, inspect design tokens (colors, typography, spacing,
  shadows), or adopt/generate a DESIGN.md for this project.
---

# Awesome DESIGN.md

A comprehensive collection of 84+ production `DESIGN.md` design system specifications extracted from world-class websites and developer-focused products.

## What is DESIGN.md?

A `DESIGN.md` file is a plain-text design system document that AI agents read to generate consistent, polished UI. It defines:
- **Color tokens**: Primary, canvas, surfaces, hairline borders, text/ink contrast, semantic accents.
- **Typography**: Font family pairings, size scales, font weights, line heights, letter-spacing.
- **Components & Layout**: Hero sections, card patterns, navbar styles, borders, radius, shadows.
- **Rhythm & Rules**: Whitespace hierarchy, micro-interactions, dark/light theme behavior.

## Available Brands (84 Design Systems)

All design specifications are stored in `./references/design-md/<brand>/DESIGN.md`:

- **Developer Tools & Cloud**: `linear.app`, `vercel`, `supabase`, `cursor`, `warp`, `posthog`, `raycast`, `resend`, `replicate`, `cloudera`, `cohere`, `clickhouse`, `mintlify`, `sanity`, `composio`, `expo`, `hashicorp`, `mongodb`, `sentry`
- **AI & Frontier Tech**: `claude`, `x.ai`, `minimax`, `mistral.ai`, `together.ai`, `ollama`, `voltagent`, `runwayml`, `lovable`, `opencode.ai`
- **Consumer, Media & Lifestyle**: `airbnb`, `apple`, `figma`, `framer`, `notion`, `nike`, `spotify`, `slack`, `tesla`, `webflow`, `wired`, `theverge`, `aljazeera`, `cal`, `clay`
- **Finance & Crypto**: `stripe`, `revolut`, `wise`, `coinbase`, `binance`, `kraken`, `mercury`, `nerdwallet`, `mastercard`
- **Automotive & Hardware**: `bmw`, `bmw-m`, `bugatti`, `ferrari`, `lamborghini`, `dji`, `tesla`, `spacex`, `oura`, `playstation`, `nintendo-2001`, `hp`, `dell-1996`
- **Enterprise & SaaS**: `airtable`, `intercom`, `shopify`, `zapier`, `starbucks`, `uber`, `vodafone`, `oracle`, `ibm`, `sap`, `mydealz`, `1win`, `jobright`

## How to Use

When the user asks to:
1. **Style or build UI like a specific company**:
   - Check `./references/design-md/<brand>/DESIGN.md`.
   - Read the color tokens, typography scales, spacing rules, and layout components.
   - Apply those exact hex codes, border radiuses, and typographic hierarchies to the code.

2. **Adopt a DESIGN.md for the workspace**:
   - Copy the chosen brand's `DESIGN.md` or adapt it into the project root as `DESIGN.md`.
   - Update project-specific brand names while keeping the tokens and rules intact.

3. **Compare or pick a design aesthetic**:
   - Review brands by mood (e.g. minimalist dark mode: `linear.app`, `cursor`, `warp`; clean modern light: `stripe`, `vercel`, `airbnb`; high-energy creative: `figma`, `raycast`, `runwayml`).

