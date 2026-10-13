# Video tutorial and promotion guide

How to record a tutorial series for Super Admin and how to make the package known. Written for the maintainer. Companion to the [User manual](user-manual.md).

## 1. What makes an admin-panel video work

Developers watch a package video to answer one question: *will this save me time on my next project?* Every episode should show a real result on screen within the first 60 seconds, then explain how it was done. Rules that hold for all episodes:

- Start with the finished screen, not with `composer require`.
- One episode = one outcome (a CRUD page, a filtered grid, a role setup). Ten minutes maximum.
- Use a realistic demo domain (a blog with posts, categories, authors) rather than `foo` / `bar`.
- Type the code on screen; do not paste large blocks. Cut the waiting (install, builds).
- Show the Tailwind Light template; mention the Classic template once with a quick switch.
- End with the command or link the viewer needs next.

## 2. Recording setup

| Item | Recommendation |
| --- | --- |
| Screen | 1920×1080, browser zoom 110–125 % so grid text is readable on phones |
| Editor | VS Code or PhpStorm, font size 16+, light theme matches the panel; hide side panels you do not use |
| Browser | A clean profile, no extensions, bookmarks bar hidden |
| Terminal | Same font size as the editor, prompt shortened to the project folder |
| Audio | Any USB microphone, quiet room, normalise to −16 LUFS in the editor |
| Recorder | OBS Studio (free): one scene with browser + editor side by side, one with editor only |
| Demo app | A fresh Laravel app with the package installed from Packagist, SQLite, seeded with 20–30 posts so grids look real |
| Repeatability | Keep a `git tag` per episode start state so you can re-record a segment without redoing the setup |

Prepare the demo app before recording with the steps in the manual's installation section, including the `admin` upload disk.

## 3. Series plan (8 episodes)

| # | Title | Outcome shown first | What you build on screen | Length |
| --- | --- | --- | --- | --- |
| 1 | Super Admin in 5 minutes | Login, dashboard, users page | `composer require`, publish, disk config, `admin:install`, login, switch template | 6 min |
| 2 | Your first CRUD | Posts grid with create/edit/show | Model + migration, `admin:controller`, route, menu item | 9 min |
| 3 | Grids that people like | Sorted, filtered, exported grid | Column helpers (`label`, `dot`, `switch`), filters, quick search, export, column selector | 9 min |
| 4 | Forms and relations | Tabbed post form with category select and tags | Field types, tabs, validation rules, `belongsTo`, `belongsToMany`, image upload | 10 min |
| 5 | Inline edit and actions | Editing titles in the grid, a "Publish" row action, a batch delete | `editable()`, `switch()`, custom `RowAction` and `BatchAction` with toast responses | 8 min |
| 6 | Users, roles and permissions | A content-editor role that can see Posts only | Roles, permission paths, menu binding, `check_route_permission` | 8 min |
| 7 | Make it yours | Rebranded panel with custom navbar and dashboard | `config/admin.php`, `bootstrap.php`, overriding a view, dashboard widgets | 8 min |
| 8 | Extensions and the road ahead | Media manager or log viewer installed | Installing an extension, `admin:extend` scaffold, where to contribute | 7 min |

Publish episodes one week apart; the first three on the same day so a new viewer can binge the basics.

## 4. Episode script template

Use this skeleton for every episode (times are targets):

1. **Cold open (0:00–0:30).** The finished screen. One sentence: what it is and that it took N minutes.
2. **Context (0:30–1:00).** What you start from (the state tag) and what the viewer needs installed.
3. **Build (1:00–7:30).** Type the code in two or three small steps; after each step, refresh the browser and point at what changed.
4. **Gotcha (7:30–8:30).** The one mistake people make here (for example forgetting `view:clear` after a template switch, or the missing `admin` disk).
5. **Next (8:30–9:00).** The command or link for the next episode and the docs page.

Keep a text file with the exact commands and code snippets for the episode open on a second monitor; the final code also goes in the video description and the docs repo.

### Example script, episode 2 (Your first CRUD)

- Cold open: show `/admin/posts` with 20 posts, open one, edit it, save. "This whole page is 40 lines of PHP."
- Context: Laravel app with Super Admin installed (episode 1), a `posts` table with title, body, status, published_at.
- Step 1: `php artisan admin:controller \App\Models\Post` and open the generated file; show the grid, form and show methods.
- Step 2: add `$router->resource('posts', PostController::class);` to `app/Admin/routes.php`; refresh `/admin/posts`.
- Step 3: add the menu item on the Menu page (title Posts, icon `icon-file`, URI `posts`); show it in the sidebar.
- Step 4: tune three columns: `->sortable()`, `->label()`, `->switch()`.
- Gotcha: the route is relative to the admin prefix; `posts`, not `/admin/posts`.
- Next: filters and export in episode 3.

## 5. Publishing checklist

- Title pattern: "Laravel admin panel in 5 minutes with Super Admin (free, open source)". Put the benefit and the word *Laravel* first.
- Thumbnail: the panel screenshot with a three-word overlay; same layout for every episode so the series is recognisable.
- Description: the commands shown, links to docs page, GitHub, Packagist, and the previous and next episode. Add chapters (timestamps).
- Playlist: "Super Admin for Laravel", episodes in order.
- Captions: upload the auto captions after correcting command names; many viewers watch muted.
- Pin a comment with the demo repository link.

## 6. Making the package known

Super Admin's position is simple and should be repeated everywhere: a complete Laravel admin builder (grid, form, show, tree, RBAC, menu, logs) that is free, has no paid tier, supports Laravel 10–13, and keeps the laravel-admin / open-admin API so existing projects can move over.

### Where to post

| Channel | What to post | When |
| --- | --- | --- |
| GitHub README | Hero screenshot of the Tailwind template, 3-command install, link to the manual and the video playlist | Before the first video |
| Packagist | Keep the description keyword-rich (laravel admin panel, crud generator, open source) | Now |
| Laravel News (laravel-news.com/links) | Submit the release post and the first video | Release day |
| Reddit r/laravel, r/PHP | A "show and tell" post with 2 screenshots and the 5-minute video; answer every comment the same day | Release week |
| X / Twitter, LinkedIn, Mastodon | One screenshot + one sentence per feature, one per day for two weeks; tag Laravel community accounts sparingly | Release week onward |
| dev.to / Medium / Hashnode | Long-form tutorials that mirror episodes 2, 4 and 6; cross-link the videos | Weekly |
| awesome-laravel lists, Laravel package directories (laravelpackages, packagist search) | Pull request adding the package | Once |
| Laracasts forum, Laravel.io | Answer "which admin panel" questions with a factual comparison and a link | Ongoing |
| YouTube Shorts / TikTok | 30-second clips: inline edit, switch toggle, template switch | Weekly |
| Bangla developer community (Facebook groups, YouTube) | The same series in Bangla; the docs already have Bangla translations | Parallel |

### Comparison people will ask for

Be factual; the package wins on price and compatibility, not on every feature.

| | Super Admin | Filament | Laravel Nova | Backpack |
| --- | --- | --- | --- | --- |
| Price | Free, MIT | Free core, paid plugins | Paid licence | Free core, paid pro |
| Approach | PHP builders (grid/form/show), Blade | Livewire components | Vue | Blade/Backpack API |
| Laravel versions | 10–13 | 10+ | 10+ | 10+ |
| Migration path | laravel-admin / open-admin projects move with few changes | New code | New code | New code |

### Things that build trust

- A public demo site (`demo.super-admin.org`) reset every hour. Mention it in every post.
- A changelog with dates and a release every 4–6 weeks, even if small.
- Response to issues within 48 hours; label `good first issue` for newcomers.
- Screenshots in the README that match the current version (re-capture with the Playwright flow used for the manual).
- A one-page "migrate from laravel-admin" guide; those projects are the largest pool of users.
- GitHub Discussions enabled for questions, so issues stay for bugs.

### Measuring

Track weekly: Packagist installs, GitHub stars, video views and average watch time, docs page views, number of open issues. Watch-time under 40 % means the episodes are too long or the result comes too late.
