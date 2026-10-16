# Node Access by Field

**Drupal 9/10/11 compatible.**
This module controls node **view access** with two complementary mechanisms:

1. **Role → User field mapping (per content type)**
   Map a role to a user reference field on that content type.
   Users with that role may view the node **only if** they are referenced in the mapped field.

2. **Per-node role visibility (optional)**
   If a node contains a multi-valued field of type **role_reference** (provided by this module) with values,
   only users holding **any of those roles** may view the node.

   > Any content type that has this field will automatically apply the rule – there is no hard-coded bundle name.

---

## Access precedence

1. **Full access roles** (global + per bundle) → ✅ ALLOW
2. **Deny roles** (global + per bundle) → ❌ FORBID
3. Node has `role_reference` values → user must have any listed role
4. Else, apply role→user field mapping
5. Otherwise → ⚪ NEUTRAL (other node access modules may decide)

---

## Installation

Install as any other Drupal module:

```bash
composer require drupal/node_access_by_field
drush en node_access_by_field -y
```

---

## Configuration

- Go to **Configuration → People → Node access by field**
  Path: `/admin/config/people/node-access-by-field`
- Permission: **Administer Node Access by Field**

---

## Per-node role visibility

To use it on a content type, **add the field** “Role reference” (machine name `role_reference`) to that content type.
You can add it via UI or Drush:

```bash
drush nabf:add-role-field <bundle> field_visible_roles
```

---

## Notes

- This module controls **view access** only.
  Extend via `hook_entity_access()` if you need **update/delete** rules.
- Views and Search API respect this access (unless you explicitly skip access checks).
- Caching: results vary by user and roles; cache contexts are added accordingly.

---

## Requirements

- Drupal Core 9, 10, or 11

No external dependencies.

---

## Maintainers

- [kate_raquel from AulaDrupal](https://www.drupal.org/u/kate_raquel)
- Sponsored by **AulaDrupal**
