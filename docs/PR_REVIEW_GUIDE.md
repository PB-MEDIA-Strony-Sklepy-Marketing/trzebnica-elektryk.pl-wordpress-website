# Pull Request Review Guide - trzebnica-elektryk.pl

**Kompleksowy przewodnik dla reviewerów Pull Requestów**

---

## 🎯 Cel tego dokumentu

Ten przewodnik pomaga:
- **Reviewerom** - co sprawdzać w PR, na co zwracać uwagę
- **Autorom PR** - czego oczekiwać podczas review
- **Team Leadom** - standardy jakości kodu

---

## ⏱️ Timeline Review

### Czas odpowiedzi

- **Standard PR:** Review w ciągu 24-48 godzin
- **Urgent/Hotfix:** Review w ciągu 2-4 godzin (max 24h)
- **Documentation-only:** Review w ciągu 24 godzin

### SLA dla różnych typów PR

| Typ PR | Review time | Approval time |
|--------|-------------|---------------|
| Hotfix (security) | 2-4h | 4-8h |
| Bugfix | 24h | 48h |
| Feature (small) | 24-48h | 48-72h |
| Feature (large) | 48-72h | 72h-1 week |
| Refactor | 48h | 72h |
| Documentation | 24h | 48h |

---

## 📋 Review Checklist

### 1. First Look (5 min)

**Przed szczegółowym review:**

- [ ] Czy PR ma opisowy tytuł?
- [ ] Czy opis PR jasno wyjaśnia "co" i "dlaczego"?
- [ ] Czy PR jest związany z issue? (Closes #XX)
- [ ] Czy PR jest odpowiedniego rozmiaru? (max 400 linii zmian to ideał)
- [ ] Czy autor wypełnił checklist w szablonie PR?
- [ ] Czy są dodane screeny (jeśli zmiany wizualne)?
- [ ] Czy CI/CD pipeline przeszedł (GitHub Actions)?

**🚨 Red Flags:**
- PR > 1000 linii zmian → poproś o rozbicie na mniejsze
- Brak opisu → poproś o uzupełnienie
- CI/CD failing → poproś o naprawę przed review
- Konflikt z `main` → poproś o rebase

---

### 2. Code Quality Review (15-30 min)

#### PHP Code

**Security (NAJWAŻNIEJSZE! 🔒)**

- [ ] Wszystkie inputy są sanityzowane:
  ```php
  // ✅ Dobrze
  $name = sanitize_text_field($_POST['name']);
  $email = sanitize_email($_POST['email']);
  
  // ❌ Źle
  $name = $_POST['name']; // Niebezpieczne!
  ```

- [ ] Wszystkie outputy są escapowane:
  ```php
  // ✅ Dobrze
  echo esc_html($user_input);
  echo '<a href="' . esc_url($link) . '">' . esc_html($text) . '</a>';
  
  // ❌ Źle
  echo $user_input; // XSS vulnerability!
  ```

- [ ] Nonce verification dla formularzy:
  ```php
  // ✅ Dobrze
  if (!wp_verify_nonce($_POST['nonce'], 'my_action')) {
      wp_die('Invalid nonce');
  }
  
  // ❌ Źle
  // Brak weryfikacji nonce
  ```

- [ ] Capability checks:
  ```php
  // ✅ Dobrze
  if (!current_user_can('edit_posts')) {
      wp_die('Unauthorized');
  }
  ```

- [ ] Prepared statements dla custom queries:
  ```php
  // ✅ Dobrze
  $results = $wpdb->get_results($wpdb->prepare(
      "SELECT * FROM {$wpdb->posts} WHERE post_title = %s",
      $title
  ));
  
  // ❌ Źle
  $results = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_title = '$title'"); // SQL Injection!
  ```

**WordPress Standards**

- [ ] Prefiks `voltmont_` dla wszystkich funkcji
- [ ] PHPDoc comments dla funkcji
- [ ] Proper hook usage (`add_action`, `add_filter`)
- [ ] No direct database access (use WP functions)
- [ ] Translatable strings: `__('Text', 'voltmont')`
- [ ] PSR-12 code style (spacje, nawiasy)

**Performance**

- [ ] No N+1 queries (check with Query Monitor)
- [ ] Transients używane dla expensive operations
- [ ] Proper cache invalidation
- [ ] No infinite loops risk
- [ ] Efficient array operations (avoid nested loops)

**Code Smells**

❌ **Unikaj:**
- Hard-coded values (use constants/options)
- Copy-paste code (use functions)
- Nested ternary operators (unreadable)
- Functions > 50 lines (split into smaller)
- God objects (classes doing too much)

---

#### JavaScript Code

**Best Practices**

- [ ] No `console.log()` in production code
- [ ] Proper error handling (`try/catch`)
- [ ] Event listeners properly removed
- [ ] No memory leaks (clean up intervals/timeouts)
- [ ] ES6+ syntax (arrow functions, const/let)
- [ ] Promises/async-await instead of callbacks

**Common Issues**

```javascript
// ✅ Dobrze
const button = document.querySelector('.btn');
if (button) {
    button.addEventListener('click', handleClick);
}

// ❌ Źle
document.querySelector('.btn').addEventListener('click', handleClick); // Może być null!
```

```javascript
// ✅ Dobrze
try {
    const data = JSON.parse(response);
} catch (error) {
    console.error('Parse error:', error);
}

// ❌ Źle
const data = JSON.parse(response); // Może throw error!
```

---

#### CSS Code

**Standards**

- [ ] Używa zmiennych CSS z `brand-system.css`
- [ ] BEM naming convention
- [ ] Mobile-first approach
- [ ] No `!important` (unless absolutely necessary)
- [ ] Proper specificity (avoid overly specific selectors)
- [ ] Vendor prefixes (autoprefixer)

**Responsive Design**

- [ ] Works on 320px width (smallest phones)
- [ ] Tested on common breakpoints (375px, 768px, 1024px, 1440px)
- [ ] No horizontal scroll on mobile
- [ ] Touch-friendly buttons (min 44x44px)

**Accessibility**

- [ ] Color contrast ≥ 4.5:1 (text)
- [ ] Focus states visible
- [ ] No animations for prefers-reduced-motion

---

### 3. Functional Review (10-20 min)

**Local Testing**

- [ ] Pull the branch: `git checkout feature/branch-name`
- [ ] Install dependencies: `npm install` (if package.json changed)
- [ ] Build: `npm run build`
- [ ] Test in browser

**What to Test**

1. **Happy Path** - czy feature działa zgodnie z oczekiwaniami?
2. **Edge Cases** - puste pola, długie teksty, specjalne znaki
3. **Error Handling** - co się dzieje gdy coś idzie źle?
4. **Performance** - czy nie spowalnia strony?
5. **Mobile** - czy działa na telefonie?
6. **Cross-browser** - przetestuj Chrome + Firefox minimum

**Tools**

- **Chrome DevTools** - Console, Network, Performance tabs
- **Responsive Mode** - test różnych rozdzielczości
- **Lighthouse** - performance, accessibility, SEO scores
- **Query Monitor** (WordPress) - database queries, hooks

---

### 4. Accessibility Review (5-10 min)

**Quick WCAG Checks**

- [ ] **Keyboard Navigation:** Tab przez wszystkie elementy interaktywne
- [ ] **Focus Visible:** Czy widać, który element jest focused?
- [ ] **Alt Text:** Wszystkie obrazy mają alt (lub alt="")
- [ ] **Labels:** Formularze mają odpowiednie `<label>` lub `aria-label`
- [ ] **Heading Structure:** h1 → h2 → h3 (bez przeskoków)
- [ ] **Color Contrast:** Użyj [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)
- [ ] **ARIA:** Czy ARIA attributes są używane poprawnie?

**Screen Reader Test (opcjonalnie)**

- NVDA (Windows, darmowy)
- VoiceOver (macOS, built-in)
- Czy kontent jest zrozumiały bez wzroku?

---

### 5. SEO Review (5 min)

**On-Page SEO**

- [ ] Meta title (50-60 znaków)
- [ ] Meta description (150-160 znaków)
- [ ] H1 tag (tylko jeden na stronę)
- [ ] Heading hierarchy (h1 → h2 → h3)
- [ ] Alt text z keywords (jeśli sensownie)
- [ ] Internal links (do innych podstron)
- [ ] Schema.org markup (jeśli dotyczy)

**Technical SEO**

- [ ] URL slug SEO-friendly (`/uslugi/instalacje-elektryczne`)
- [ ] No broken links
- [ ] Canonical URL set (if needed)
- [ ] OpenGraph tags (for social sharing)
- [ ] Robots meta tag correct

**Tools**

- [Google Rich Results Test](https://search.google.com/test/rich-results) - schema validation
- [PageSpeed Insights](https://pagespeed.web.dev/) - performance impact on SEO

---

### 6. Performance Review (5-10 min)

**Core Web Vitals**

Target metrics:
- **LCP** (Largest Contentful Paint) < 2.5s
- **FID** (First Input Delay) < 100ms
- **CLS** (Cumulative Layout Shift) < 0.1

**What to Check**

- [ ] Images optimized (WebP format, lazy loading)
- [ ] No render-blocking resources
- [ ] Critical CSS inlined (if applicable)
- [ ] JavaScript bundles < 50kB
- [ ] CSS bundles < 30kB
- [ ] No unnecessary console logs/debuggers
- [ ] Proper caching headers

**Tools**

- Lighthouse (in Chrome DevTools)
- `npm run analyze` - bundle size analysis
- Query Monitor - database query performance

---

## 💬 How to Leave Feedback

### Tone & Style

**✅ Good Feedback:**
- Specific and constructive
- Explains "why" not just "what"
- Suggests solution
- Respectful and encouraging

**❌ Bad Feedback:**
- Vague ("fix this")
- Demanding tone
- No explanation
- Discouraging

### Examples

**✅ Good:**
```
Consider adding input sanitization here:

$email = sanitize_email($_POST['email']);

This prevents XSS attacks. See SECURITY.md for more info.
```

**❌ Bad:**
```
This is wrong. Fix it.
```

---

**✅ Good:**
```
Nice work on the mobile layout! 

One suggestion: the button is a bit small on 360px screens (38px height). 
WCAG recommends minimum 44x44px for touch targets.

Could you bump it to 44px?
```

**❌ Bad:**
```
Button too small.
```

---

### Comment Types

GitHub has 3 types of comments:

1. **Comment** - neutral observation, question
2. **Approve** - PR is good to merge
3. **Request Changes** - blocker issues that must be fixed

**When to Request Changes:**
- Security vulnerabilities
- Broken functionality
- Failing tests
- Major performance issues
- WCAG violations (if accessibility PR)

**When to Approve (with optional comments):**
- Minor code style issues
- Nice-to-have improvements
- Everything works, no blockers

---

## 🚦 Review Statuses

### 🟢 Approved

**Criteria:**
- All checklist items passed
- No security issues
- No breaking bugs
- Code meets quality standards
- Tests pass
- Documentation updated (if needed)

**Next Steps:**
- Merge to `main` (or merge after client approval)
- Deploy to staging
- Monitor production

---

### 🟡 Approved with Comments

**Criteria:**
- No blocking issues
- Minor suggestions for improvement
- Nice-to-have changes

**Next Steps:**
- Author can merge after addressing comments
- Or merge now and create follow-up issue

---

### 🔴 Request Changes

**Criteria:**
- Security vulnerabilities
- Broken functionality
- Failing tests
- Major code quality issues
- Missing required documentation

**Next Steps:**
- Author must fix issues
- Request re-review after fixes
- Do NOT merge until approved

---

## 🔄 Re-Review Process

**After author addresses changes:**

1. Author comments: "Ready for re-review" + mentions reviewer
2. Reviewer checks specific changes (not full review again)
3. Approve or request more changes

**Time expectations:**
- Re-review of minor changes: 2-4 hours
- Re-review of major changes: 24 hours

---

## 🎓 Tips for Effective Reviews

### For Reviewers

**Do:**
- ✅ Review promptly (24-48h max)
- ✅ Test locally for non-trivial changes
- ✅ Ask questions if something is unclear
- ✅ Acknowledge good work ("Nice solution!")
- ✅ Use GitHub suggestions feature for quick fixes
- ✅ Check the "Files changed" tab line-by-line

**Don't:**
- ❌ Review when tired/rushed (you'll miss things)
- ❌ Approve without testing
- ❌ Be nitpicky about style (that's what linters are for)
- ❌ Request changes for personal preferences
- ❌ Ghost the PR (give feedback or approve!)

---

### For Authors

**Do:**
- ✅ Self-review before requesting review (catch obvious issues)
- ✅ Keep PRs small and focused (< 400 lines ideal)
- ✅ Fill out PR template completely
- ✅ Add screenshots for UI changes
- ✅ Respond to feedback promptly
- ✅ Ask questions if feedback is unclear
- ✅ Run all checks before pushing (`npm run preflight`)

**Don't:**
- ❌ Submit WIP (work in progress) without marking as draft
- ❌ Argue with every comment (be open to feedback)
- ❌ Fix issues from review in new commits (squash or fixup)
- ❌ Take feedback personally (it's about code, not you!)
- ❌ Rush reviewer (unless urgent hotfix)

---

## 🔥 Hotfix Review Process

**For critical bugs/security issues:**

1. **Author:** Mark PR as "urgent" or "hotfix" in title
2. **Author:** Explain impact and why it's urgent
3. **Reviewer:** Prioritize review (2-4 hours max)
4. **Reviewer:** Focus on security + no breaking changes
5. **Merge:** Fast-track to production
6. **Follow-up:** Create issue for proper fix if hotfix is temporary

---

## 📊 Metrics & Improvement

**Track these metrics:**
- Average PR review time
- Average PR size (lines changed)
- Number of review iterations
- % of PRs with security issues caught

**Retrospectives:**
- Monthly review of PR process
- What's working?
- What can be improved?
- Update this guide accordingly

---

## 📚 Resources

### Tools
- [GitHub Pull Request Guide](https://docs.github.com/en/pull-requests)
- [Google's Code Review Guidelines](https://google.github.io/eng-practices/review/)
- [Conventional Comments](https://conventionalcomments.org/)

### Internal Docs
- [SECURITY.md](SECURITY.md) - Security guidelines
- [CONTRIBUTING.md](CONTRIBUTING.md) - Contribution process
- [CLAUDE.md](CLAUDE.md) - Development standards
- [TESTING.md](TESTING.md) - Testing requirements

---

## 🆘 Questions?

**For help with reviews:**
- Email: biuro@pbmediaonline.pl
- Tag team lead in PR comments

**For process improvements:**
- Create issue with label "process"
- Discuss in team retrospective

---

**Happy reviewing! 🚀**

---

*Last updated: 2024-11-25*  
*Version: 1.0*
