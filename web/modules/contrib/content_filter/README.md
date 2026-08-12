# CONTENT FILTER

## INTRODUCTION

Content Filter adds dedicated management pages for selected content types,
improving how site builders and editors interact with content in the Drupal administration area.

**Typical use cases**:
- Editorial teams managing multiple content types
- Large or long-running sites with lots of content
- Projects where non-technical users need clear, focused admin pages
- Sites that want a cleaner, more structured content management experience

## FEATURES

**What does it do?**

- It creates **content-specific listing pages** inside the Content administration section.
- Allows site builders to **choose which content types** get their own pages.
- Optionally generates **submenu links under the Content menu** for quick access.
- Each page displays only one content type, with familiar filters and actions.

**Why use this module?**
Drupal’s default content overview mixes all content types together.
On sites with many content types, this becomes difficult to manage especially for editors new to Drupal.

Content Filter solves this by:

- Reducing clutter in the Content overview
- Making content **faster and easier to locate**
- Giving editors clear, purpose-driven admin pages

## REQUIREMENTS
The only requirement is to have the default **Content** view enabled.

No external libraries or third-party APIs required.
Any additional dependencies are declared in the module’s .info.yml file.

## INSTALLATION
Install and enable as you would normally install a contributed Drupal module.

See: [Installing modules](https://www.drupal.org/node/895232) for further information.

## CONFIGURATION
After enabling the module,
a configuration form becomes available where site builders can control how Content Filter behaves.

**Configuration steps**

- Go to the module’s **configuration page** located under Configuration → User interface.
- Select which **content types** should have dedicated management pages.
- Choose whether the module should:
  - Add **submenu items under the Content menu**, or
  - Keep the pages accessible only by sub tabs without modifying the menu structure.
- Save the configuration.

**What happens next?**
- For each selected content type, a dedicated content listing page is generated.
- If enabled, submenu links appear under **Content**, allowing editors to jump directly to a specific content type.
- No new content types are created.
- Existing content, permissions, and text formats are not modified.

Editors can immediately start using the new pages to manage content more efficiently.

## MAINTAINERS
Current maintainers for Drupal 11:

- Eugeni Masclans (emasclans) - https://www.drupal.org/u/emasclans
