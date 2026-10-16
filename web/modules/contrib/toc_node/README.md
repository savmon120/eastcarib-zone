# TOC Node Module

## Description

Provides a Table Of Contents for nodes, automatically generated from heading tags (h2–h6). The TOC can appear inline at the top of the content region and/or as a reusable block in any region.

## Features

- **Bullets display** — indented bullet list by heading level
- **Numbered display** — multi-level numbered list (e.g. 1, 1.1, 1.2, 2)
- **Heading anchors** — each heading gets a named anchor (`#toc-N`) so TOC links scroll to the correct position
- **Block TOC** — place a TOC block in any region (sidebar, footer, etc.) independently of the inline TOC
- **Block-level overrides** — each block placement can set its own display style, heading depth, and back-to-top behavior, separate from the node
- **"No TOC" per-node** — suppresses the built-in inline TOC while keeping heading anchors for block TOC links
- **Back to top button** — fixed-position button appears after scrolling down, scrolls smoothly to the top on click
- **Per Content Type defaults** — configure default style, available styles, heading depth, and back-to-top mode per content type
- **Per-node overrides** — individual nodes can override the content type defaults

## Requirements

- Drupal 10 or 11
- PHP 8.1+
- `ext-dom` and `ext-libxml` PHP extensions (included in most PHP installations)

## Installation

Install as you would normally install a contributed Drupal module. For further information, see [Installing Drupal Modules](https://www.drupal.org/docs/extending-drupal/installing-modules).

## Configuration

### Content type defaults

Administer per-content-type settings at:
`/admin/structure/types/manage/{type}`

| Setting | Description |
|---|---|
| **Enable table of contents** | Globally enables/disables TOC for this content type |
| **Styles available** | Which display styles are offered to editors (No TOC, Bullets, Numbered) |
| **Display default** | Default style for new nodes (None = no inline TOC, anchors only) |
| **Level** | Maximum heading depth to include in the inline list (h2–h6). All h2–h6 headings are anchored regardless of this setting, so block TOCs can link to deeper headings. |
| **Back to top links** | Controls the back-to-top button: always off, always on, or per-node |

### Per-node overrides

Each node can override the content type defaults via the **Table of
contents** fieldset on the node edit form. Per-node settings take
precedence over content type defaults:

- **TOC Display** — choose from the styles made available on the
  content type. Selecting "No TOC" hides the built-in inline TOC but keeps heading anchors so block TOC links still work.
- **Back to top links** — visible only when the content type mode is set to "Enabled - per node option" or "Disabled - per node option".
- **Level** — override the maximum heading depth for the inline list.

Every h2–h6 heading is given an `id="toc-N"` anchor regardless of the
configured level. This keeps block-placed TOCs working even when the node's
inline TOC is limited to a shallower depth (or set to "No TOC").

### Block placement

The **Table Of Contents** block can be placed in any region at:
`/admin/structure/block`

Each block instance has independent configuration:

| Setting | Description |
|---|---|
| **Display style** | Override the node/content type style (Bullets or Numbered). Leave empty to inherit from the node. "No TOC" nodes still render the block using bullets. |
| **Heading level** | Override the heading depth. Leave empty to inherit. |
| **Back to top** | Show a back-to-top button in the block content. |

When both the inline TOC and block TOC are present, they render
independently. The block always displays regardless of the node's "No TOC" setting.

## Usage

Once enabled and configured, the TOC is generated automatically on node pages viewed in **Full** display mode.

### Bullets style

Renders as an indented unordered list with bullet icons. Each level is indented further to show hierarchy.

### Numbered style

Renders as a multi-level numbered list:

```text
1
2
  2.1
  2.2
3
```

### No TOC style ("anchors only")

Selecting "No TOC" on a node hides the inline TOC list and back-to-top button, but every h2–h6 heading is still anchored with an `id="toc-N"`. This allows block-placed TOCs to link to headings on pages where the inline TOC is not desired.

### Back-to-top

When enabled, a fixed "Back to Top" button appears in the bottom-right corner after scrolling down 1000px. Clicking it scrolls smoothly to the top of the page. It can be controlled globally (always on/off) or per-node.

## Cache

The block uses the `url.path` and `user.permissions` cache contexts. Clear caches after configuration changes.

## Maintainers

Current maintainers:

- [Robert Castelo](https://www.drupal.org/u/robert-castelo)

