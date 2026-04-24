# ❤️ Volunteer Spotlight — WordPress Plugin

![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple?logo=php)
![License](https://img.shields.io/badge/License-GPLv2-green)
![Version](https://img.shields.io/badge/Version-1.3.0-red)

**Showcase your amazing volunteers with beautiful, professional spotlight cards displayed in a responsive slider.**

Built for [Brooklyn Emerge](https://brooklynemerge.com) — a community-focused nonprofit organization.

---

## ✨ Features

- 🎨 **Beautiful Card Design** — Modern, clean cards with photo, name, designation & description
- 🎠 **Smooth Slider** — Powered by Swiper.js with autoplay, navigation arrows & pagination dots
- ❤️ **Decorative Accents** — Heart icon overlay, dot patterns & geometric shapes
- 📱 **Fully Responsive** — Looks great on desktop, tablet & mobile
- ⚡ **Lightweight** — CSS & JS only load on pages where the shortcode is used
- 🛠️ **Easy Admin Panel** — Simple post editor with name, designation, photo & description fields
- 🔒 **Secure** — Nonce verification, data sanitization & directory browsing protection
- 🧹 **Clean Uninstall** — Removes all data when the plugin is deleted

---

## 📸 Preview

| Feature | Description |
|---|---|
| **Left Side** | Full-height volunteer photo with hover zoom effect |
| **Heart Icon** | Pulsing red heart overlay on the photo |
| **Badge** | Red "★ Volunteer Spotlight" pill badge |
| **Name** | Large, bold heading |
| **Designation** | Red subtitle text (role / title) |
| **Description** | Volunteer's story or impact quote |
| **Read More** | Red button with arrow animation |
| **Decorations** | Dot pattern & geometric triangle accents |

---

## 📦 Installation

### Option 1 — Upload ZIP (Recommended)

1. Download the latest release ZIP from the [Releases](https://github.com/SMY-Solutions/volunteer/releases) page
2. Go to **WordPress Admin → Plugins → Add New → Upload Plugin**
3. Choose the `.zip` file and click **Install Now**
4. Click **Activate**

### Option 2 — Manual Upload

1. Download or clone this repository
2. Copy the `volunteer-spotlight` folder to `wp-content/plugins/` on your server
3. Go to **WordPress Admin → Plugins** and activate **Volunteer Spotlight**

---

## 🚀 Usage

### Adding a Volunteer

1. In WordPress admin, go to **Vol. Spotlight** (❤️ heart icon in sidebar)
2. Click **Add Spotlight**
3. Fill in the details:

| Field | Where to Enter |
|---|---|
| **Name** | Post title field |
| **Designation** | Custom "Designation / Role" field |
| **Description** | Rich text editor in the meta box |
| **Photo** | "Set featured image" in the right sidebar |

4. Click **Publish**

### Displaying the Slider

Add the shortcode to **any page or post**:

```
[volunteer_spotlight]
```

### Shortcode Options

| Attribute | Default | Description |
|---|---|---|
| `limit` | `-1` (all) | Maximum number of volunteers to display |
| `autoplay` | `true` | Auto-rotate the slider |
| `speed` | `4000` | Autoplay delay in milliseconds |
| `order` | `DESC` | Sort order (`ASC` or `DESC`) |

#### Examples

```
[volunteer_spotlight limit="6" autoplay="true" speed="5000"]
```

```
[volunteer_spotlight limit="3" autoplay="false" order="ASC"]
```

---

## 📁 Plugin Structure

```
volunteer-spotlight/
├── volunteer-spotlight.php       ← Main plugin file
├── readme.txt                    ← WordPress readme
├── uninstall.php                 ← Cleanup on deletion
├── index.php                     ← Security
├── admin/
│   ├── index.php
│   └── css/
│       ├── admin-style.css       ← Admin panel styles
│       └── index.php
└── public/
    ├── index.php
    ├── css/
    │   ├── spotlight-style.css   ← Frontend card & slider styles
    │   └── index.php
    └── js/
        ├── spotlight-script.js   ← Swiper initialization
        └── index.php
```

---

## 🔧 Requirements

- **WordPress** 5.0 or higher
- **PHP** 7.4 or higher
- Internet connection (loads Swiper.js from CDN)

---

## 📝 Changelog

### v1.3.0 — Stable Release
- **Hardcoded inline CSS & JS** — Zero file-path dependency; styles always render regardless of server folder name
- **Read More button** now links to the volunteer's actual post permalink
- **Enhanced card shadow** updated to match reference design
- **Photo section** now has left-side rounded corners matching the card
- **CSS specificity** improved with `!important` overrides for Elementor/theme conflicts
- **Swiper conflict handling** — checks for existing handle before registering to avoid version clashes

### v1.2.0
- Replaced heart & star emojis with high-quality Font Awesome icons
- Fixed CSS enqueuing priority to prevent theme overrides
- Flattened ZIP structure for better WordPress compatibility
- Improved card layout and responsive refinements

### v1.0.0 — Initial Release
- Custom Post Type for volunteer spotlights
- Meta boxes for designation & description
- Swiper.js powered responsive slider
- Shortcode with configurable options
- Admin columns with photo thumbnail preview
- Full mobile responsiveness
- Security hardening (nonces, sanitization, index files)
- Clean uninstall support

---

## 👨‍💻 Credits

| Role | Name |
|---|---|
| **Developer** | Muhammad Mehdi |
| **Designer** | Muhammad Mehdi |
| **Author** | Muhammad Mehdi |

---

## 📄 License

This plugin is licensed under the [GNU General Public License v2.0](https://www.gnu.org/licenses/gpl-2.0.html) or later.

---

<p align="center">
  Made with ❤️ by <strong>Muhammad Mehdi</strong> for <strong>Brooklyn Emerge</strong>
</p>
