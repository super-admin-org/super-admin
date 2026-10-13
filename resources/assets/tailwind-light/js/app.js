/**
 * Super Admin - Tailwind Light Template
 *
 * UI component replacements for Bootstrap JS
 * Provides: dropdown, modal, tab, collapse, tooltip, popover.
 *
 * Also exposes a minimal `window.bootstrap` compatibility layer (Modal, Popover,
 * Tooltip, Tab, Collapse, Dropdown) so the shared admin scripts and PHP emitted
 * scripts keep working without Bootstrap being loaded.
 */

const SuperAdminUI = {
  _bound: false,

  /**
   * Initialize all UI components on the page.
   * Document-level listeners are bound only once; per-element
   * initializers are idempotent so this is safe to call after PJAX loads.
   */
  init() {
    if (!this._bound) {
      this._bound = true;
      this.initDropdowns();
      this.initCollapses();
      this.initTabs();
      this.initModals();
    }
    this.initTooltips();
  },

  /* ------------------------------------------------------------------ */
  /* helpers                                                            */
  /* ------------------------------------------------------------------ */

  resolve(target) {
    if (!target) return null;
    if (target instanceof Element) return target;
    if (typeof target !== 'string' || target === '#' || target === '') return null;
    try {
      return document.querySelector(target);
    } catch (_) {
      return null;
    }
  },

  targetOf(trigger) {
    const explicit = trigger.getAttribute('data-sa-target') || trigger.getAttribute('data-bs-target');
    if (explicit) return explicit;
    const href = trigger.getAttribute('href') || '';
    return /^#./.test(href) ? href : null;
  },

  fire(el, name, detail) {
    const event = new CustomEvent(name, { bubbles: true, cancelable: true, detail: detail || {} });
    if (detail && detail.relatedTarget) {
      event.relatedTarget = detail.relatedTarget;
    }
    el.dispatchEvent(event);
    return event;
  },

  /* ------------------------------------------------------------------ */
  /* Dropdown                                                           */
  /* ------------------------------------------------------------------ */

  dropdownMenu(trigger) {
    const explicit = trigger.getAttribute('data-sa-target') || trigger.getAttribute('data-bs-target');
    if (explicit) return this.resolve(explicit);
    const next = trigger.nextElementSibling;
    if (next) return next;
    return trigger.parentElement ? trigger.parentElement.querySelector('.dropdown-menu') : null;
  },

  openDropdown(menu, trigger) {
    if (trigger) this.fire(trigger, 'show.bs.dropdown', { relatedTarget: trigger });
    menu.classList.remove('hidden');
    menu.classList.add('sa-dropdown-open', 'show');
    if (trigger) {
      trigger.setAttribute('aria-expanded', 'true');
      menu._saTrigger = trigger;
    }
  },

  closeDropdown(menu) {
    const trigger = menu._saTrigger;
    if (trigger) this.fire(trigger, 'hide.bs.dropdown', { relatedTarget: trigger });
    menu.classList.add('hidden');
    menu.classList.remove('sa-dropdown-open', 'show');
    if (trigger) trigger.setAttribute('aria-expanded', 'false');
  },

  closeDropdowns(except) {
    document.querySelectorAll('.sa-dropdown-open').forEach((menu) => {
      if (menu !== except) this.closeDropdown(menu);
    });
  },

  /**
   * Dropdown - Click to toggle a dropdown menu.
   * Supports data-sa-target / data-bs-target or falls back to nextElementSibling.
   */
  initDropdowns() {
    document.addEventListener('click', (e) => {
      const trigger = e.target.closest('[data-sa-toggle="dropdown"], [data-bs-toggle="dropdown"]');

      if (trigger) {
        e.preventDefault();
        const menu = this.dropdownMenu(trigger);
        this.closeDropdowns(menu);
        if (menu) {
          if (menu.classList.contains('sa-dropdown-open')) {
            this.closeDropdown(menu);
          } else {
            this.openDropdown(menu, trigger);
          }
        }
        return;
      }

      const openMenu = e.target.closest('.sa-dropdown-open');
      if (openMenu) {
        // Keep menus with form controls open; close when a link / dismiss item is clicked.
        if (e.target.closest('a, [data-sa-dismiss="dropdown"], [data-bs-dismiss="dropdown"]')) {
          this.closeDropdown(openMenu);
        }
        return;
      }

      this.closeDropdowns();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') this.closeDropdowns();
    });
  },

  /* ------------------------------------------------------------------ */
  /* Collapse                                                           */
  /* ------------------------------------------------------------------ */

  collapseShow(target, trigger) {
    target.classList.remove('hidden');
    target.classList.add('show');
    const icon = trigger ? trigger.querySelector('.collapse-icon') : null;
    if (icon) icon.classList.add('rotate-90');
    if (trigger) trigger.setAttribute('aria-expanded', 'true');
    this.fire(target, 'shown.bs.collapse');
  },

  collapseHide(target, trigger) {
    target.classList.add('hidden');
    target.classList.remove('show');
    const icon = trigger ? trigger.querySelector('.collapse-icon') : null;
    if (icon) icon.classList.remove('rotate-90');
    if (trigger) trigger.setAttribute('aria-expanded', 'false');
    this.fire(target, 'hidden.bs.collapse');
  },

  collapseToggle(target, trigger) {
    if (target.classList.contains('hidden')) {
      this.collapseShow(target, trigger);
    } else {
      this.collapseHide(target, trigger);
    }
  },

  /**
   * Collapse - Toggle visibility of a target element.
   * Inside #sidebar: accordion behavior (close sibling collapses when opening one).
   */
  initCollapses() {
    document.addEventListener('click', (e) => {
      const trigger = e.target.closest('[data-sa-toggle="collapse"], [data-bs-toggle="collapse"]');
      if (!trigger) return;
      e.preventDefault();
      const target = this.resolve(this.targetOf(trigger));
      if (!target) return;

      const isInSidebar = !!trigger.closest('#sidebar');

      if (isInSidebar) {
        const isCurrentlyHidden = target.classList.contains('hidden');

        // Accordion: close all other sidebar collapses at the same depth level
        const parentLi = trigger.closest('li');
        const parentUl = parentLi ? parentLi.parentElement : null;
        if (parentUl) {
          parentUl.querySelectorAll(':scope > li > [data-sa-toggle="collapse"]').forEach((btn) => {
            if (btn === trigger) return;
            const other = this.resolve(this.targetOf(btn));
            if (other && !other.classList.contains('hidden')) {
              this.collapseHide(other, btn);
            }
          });
        }

        if (isCurrentlyHidden) {
          this.collapseShow(target, trigger);
        } else {
          this.collapseHide(target, trigger);
        }
      } else {
        this.collapseToggle(target, trigger);
      }
    });
  },

  /* ------------------------------------------------------------------ */
  /* Sidebar                                                            */
  /* ------------------------------------------------------------------ */

  /**
   * Update sidebar active link and auto-expand its parent submenu.
   * Called on initial load and after every PJAX navigation.
   */
  updateSidebarActive() {
    const currentPath = window.location.pathname;

    document.querySelectorAll('#sidebar a.glass-menu-item').forEach((el) => {
      el.classList.remove('glass-menu-item-active');
    });

    document.querySelectorAll('#sidebar ul[id^="collapse-"]').forEach((ul) => {
      ul.classList.add('hidden');
      ul.classList.remove('show');
      const icon = document.querySelector(`[data-sa-target="#${ul.id}"] .collapse-icon`);
      if (icon) icon.classList.remove('rotate-90');
    });

    let bestMatch = null;
    let bestLen = 0;
    document.querySelectorAll('#sidebar a.glass-menu-item').forEach((link) => {
      const href = link.getAttribute('href');
      if (!href) return;
      try {
        const linkPath = new URL(href, window.location.origin).pathname.replace(/\/$/, '');
        const cur = currentPath.replace(/\/$/, '');
        if (cur === linkPath || cur.startsWith(linkPath + '/')) {
          if (linkPath.length > bestLen) {
            bestLen = linkPath.length;
            bestMatch = link;
          }
        }
      } catch (_) {}
    });

    if (bestMatch) {
      bestMatch.classList.add('glass-menu-item-active');

      let el = bestMatch.parentElement;
      while (el && el.id !== 'menu') {
        if (el.tagName === 'UL' && el.id && el.id.startsWith('collapse-')) {
          el.classList.remove('hidden');
          el.classList.add('show');
          const icon = document.querySelector(`[data-sa-target="#${el.id}"] .collapse-icon`);
          if (icon) icon.classList.add('rotate-90');
        }
        el = el.parentElement;
      }
    }
  },

  /* ------------------------------------------------------------------ */
  /* Tabs                                                               */
  /* ------------------------------------------------------------------ */

  /**
   * Activate a tab link: deactivate its siblings, hide sibling panels, show its panel.
   * Works for glass tabs (data-sa-toggle="tab") and Bootstrap style tabs (.nav-tabs .nav-link).
   */
  activateTab(tab) {
    if (!tab) return;
    const panelSelector = tab.getAttribute('data-bs-target') || tab.getAttribute('href');
    const panel = this.resolve(panelSelector);
    const nav = tab.closest('.nav-tabs, .glass-tabs, .nav, nav, ul');
    const container = this.resolve(tab.getAttribute('data-sa-target')) || (panel ? panel.parentElement : null);

    if (nav) {
      nav.querySelectorAll('[data-sa-toggle="tab"], [data-bs-toggle="tab"], .nav-link').forEach((t) => {
        t.classList.remove('active', 'glass-tab-active');
        t.setAttribute('aria-selected', 'false');
      });
    }

    if (container) {
      container.querySelectorAll(':scope > .tab-pane, :scope > [data-sa-tab-panel]').forEach((p) => {
        p.classList.add('hidden');
        p.classList.remove('active', 'show');
      });
    }

    tab.classList.add('active', 'glass-tab-active');
    tab.setAttribute('aria-selected', 'true');

    if (panel) {
      panel.classList.remove('hidden');
      panel.classList.add('active', 'show');
    }

    this.fire(tab, 'shown.bs.tab', { relatedTarget: tab });
  },

  initTabs() {
    document.addEventListener('click', (e) => {
      const tab = e.target.closest('[data-sa-toggle="tab"], [data-bs-toggle="tab"]');
      if (!tab) return;
      e.preventDefault();
      this.activateTab(tab);
    });
  },

  /* ------------------------------------------------------------------ */
  /* Modal                                                              */
  /* ------------------------------------------------------------------ */

  /**
   * Both .glass-modal-backdrop and .glass-modal are shown/hidden together.
   * A trigger may target either the modal id or the backdrop id.
   * Legacy Bootstrap markup (.modal) is supported as well.
   */
  modalElement(target) {
    let el = this.resolve(target);
    if (!el) return null;
    if (el.classList.contains('glass-modal-backdrop') && el.id) {
      el = this.resolve('#' + el.id.replace(/-backdrop$/, '')) || el;
    }
    return el;
  },

  modalBackdrop(modal, create) {
    if (!modal.id) return null;
    let backdrop = this.resolve('#' + modal.id + '-backdrop');
    if (!backdrop && create) {
      backdrop = document.createElement('div');
      backdrop.id = modal.id + '-backdrop';
      backdrop.className = 'glass-modal-backdrop hidden';
      modal.parentNode.insertBefore(backdrop, modal);
    }
    return backdrop;
  },

  isModalOpen(modal) {
    if (modal.classList.contains('hidden')) return false;
    return modal.classList.contains('glass-modal') ? true : modal.classList.contains('show');
  },

  _modalOpen(target, relatedTarget) {
    const modal = this.modalElement(target);
    if (!modal) return;
    if (this.isModalOpen(modal)) return;

    const showEvent = this.fire(modal, 'show.bs.modal', { relatedTarget: relatedTarget || null });
    if (showEvent.defaultPrevented) return;

    const backdrop = this.modalBackdrop(modal, true);
    if (backdrop) backdrop.classList.remove('hidden');

    modal.classList.remove('hidden', 'fade');
    modal.classList.add('show');
    if (!modal.classList.contains('glass-modal')) {
      modal.classList.add('sa-modal-legacy');
    }
    document.body.classList.add('overflow-hidden');

    this.fire(modal, 'shown.bs.modal', { relatedTarget: relatedTarget || null });
  },

  _modalClose(el) {
    let modal = null;
    if (el instanceof Element) {
      modal = el.classList.contains('glass-modal') || el.classList.contains('modal') ? el : el.closest('.glass-modal, .modal, .glass-modal-backdrop');
    } else {
      modal = this.modalElement(el);
    }
    if (!modal) return;

    const target = modal.classList.contains('glass-modal-backdrop') ? this.modalElement(modal) : modal;
    if (!target) return;

    const hideEvent = this.fire(target, 'hide.bs.modal');
    if (hideEvent.defaultPrevented) return;

    target.classList.add('hidden');
    target.classList.remove('show');
    const backdrop = this.modalBackdrop(target, false);
    if (backdrop) backdrop.classList.add('hidden');

    if (!document.querySelector('.glass-modal:not(.hidden), .modal.show')) {
      document.body.classList.remove('overflow-hidden');
    }

    this.fire(target, 'hidden.bs.modal');
  },

  initModals() {
    document.addEventListener('click', (e) => {
      const trigger = e.target.closest('[data-sa-toggle="modal"], [data-bs-toggle="modal"]');
      if (trigger) {
        e.preventDefault();
        const targetId = this.targetOf(trigger);
        if (targetId) this._modalOpen(targetId, trigger);
        return;
      }

      const closeBtn = e.target.closest('[data-sa-dismiss="modal"], [data-bs-dismiss="modal"], .btn-close');
      if (closeBtn) {
        e.preventDefault();
        const explicit = closeBtn.getAttribute('data-sa-target') || closeBtn.getAttribute('data-bs-target');
        this._modalClose(explicit ? this.modalElement(explicit) || closeBtn : closeBtn);
        return;
      }

      if (
        e.target.classList.contains('glass-modal') ||
        e.target.classList.contains('glass-modal-backdrop') ||
        e.target.classList.contains('sa-modal-legacy')
      ) {
        this._modalClose(e.target);
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        document.querySelectorAll('.glass-modal:not(.hidden), .modal.show').forEach((m) => {
          this._modalClose(m);
        });
      }
    });
  },

  /* ------------------------------------------------------------------ */
  /* Tooltips                                                           */
  /* ------------------------------------------------------------------ */

  initTooltips() {
    document.querySelectorAll('[data-sa-tooltip], [data-bs-toggle="tooltip"]').forEach((el) => {
      if (SaTooltip.getInstance(el)) return;
      new SaTooltip(el);
    });
  },
};

/* -------------------------------------------------------------------- */
/* Floating elements (tooltip / popover)                                */
/* -------------------------------------------------------------------- */

function positionFloating(tip, reference, placement) {
  const rect = reference.getBoundingClientRect();
  const scrollX = window.pageXOffset;
  const scrollY = window.pageYOffset;
  const gap = 8;
  let top;
  let left;

  tip.style.position = 'absolute';
  tip.style.visibility = 'hidden';
  tip.style.display = 'block';

  const w = tip.offsetWidth;
  const h = tip.offsetHeight;
  let place = placement || 'top';

  if (place === 'top' && rect.top - h - gap < 0) place = 'bottom';
  if (place === 'bottom' && rect.bottom + h + gap > window.innerHeight && rect.top - h - gap >= 0) place = 'top';

  switch (place) {
    case 'bottom':
      top = rect.bottom + scrollY + gap;
      left = rect.left + scrollX + rect.width / 2 - w / 2;
      break;
    case 'left':
      top = rect.top + scrollY + rect.height / 2 - h / 2;
      left = rect.left + scrollX - w - gap;
      break;
    case 'right':
      top = rect.top + scrollY + rect.height / 2 - h / 2;
      left = rect.right + scrollX + gap;
      break;
    default:
      top = rect.top + scrollY - h - gap;
      left = rect.left + scrollX + rect.width / 2 - w / 2;
  }

  const maxLeft = scrollX + document.documentElement.clientWidth - w - 8;
  left = Math.max(scrollX + 8, Math.min(left, maxLeft));
  top = Math.max(scrollY + 4, top);

  tip.style.top = top + 'px';
  tip.style.left = left + 'px';
  tip.style.visibility = '';
  tip.setAttribute('data-placement', place);
}

class SaTooltip {
  constructor(element, config) {
    this._element = element;
    this._config = Object.assign({ placement: 'top', trigger: 'hover focus' }, config || {});
    this.tip = null;
    SaTooltip.instances.set(element, this);

    if (this._config.trigger !== 'manual') {
      element.addEventListener('mouseenter', () => this.show());
      element.addEventListener('mouseleave', () => this.hide());
      element.addEventListener('focus', () => this.show());
      element.addEventListener('blur', () => this.hide());
    }
  }

  static getInstance(element) {
    return SaTooltip.instances.get(element) || null;
  }

  static getOrCreateInstance(element, config) {
    return SaTooltip.getInstance(element) || new SaTooltip(element, config);
  }

  _title() {
    const el = this._element;
    if (typeof this._config.title === 'function') return this._config.title.call(el);
    return (
      this._config.title ||
      el.getAttribute('data-sa-tooltip') ||
      el.getAttribute('data-bs-title') ||
      el.getAttribute('data-bs-original-title') ||
      el.getAttribute('title') ||
      ''
    );
  }

  show() {
    const text = this._title();
    if (!text) return;
    if (this._element.hasAttribute('title')) {
      this._element.setAttribute('data-bs-original-title', this._element.getAttribute('title'));
      this._element.removeAttribute('title');
    }
    if (!this.tip) {
      this.tip = document.createElement('div');
      this.tip.className = 'sa-tooltip';
      this.tip.setAttribute('role', 'tooltip');
    }
    this.tip.textContent = text;
    document.body.appendChild(this.tip);
    positionFloating(this.tip, this._element, this._config.placement);
    this.tip.classList.add('show');
  }

  hide() {
    if (this.tip && this.tip.parentNode) {
      this.tip.classList.remove('show');
      this.tip.parentNode.removeChild(this.tip);
    }
  }

  toggle() {
    if (this.tip && this.tip.parentNode) {
      this.hide();
    } else {
      this.show();
    }
  }

  dispose() {
    this.hide();
    SaTooltip.instances.delete(this._element);
  }
}
SaTooltip.instances = new WeakMap();

class SaPopover {
  constructor(element, config) {
    this._element = element;
    this._config = Object.assign({ placement: 'top', trigger: 'click', html: false, container: 'body' }, config || {});
    this._shown = false;
    // The tip element persists across show/hide so listeners attached to it survive.
    this.tip = document.createElement('div');
    this.tip.className = 'sa-popover popover';
    this.tip.setAttribute('role', 'tooltip');
    SaPopover.instances.set(element, this);

    if (this._config.trigger === 'click') {
      element.addEventListener('click', (e) => {
        e.preventDefault();
        this.toggle();
      });
    } else if (this._config.trigger === 'hover') {
      element.addEventListener('mouseenter', () => this.show());
      element.addEventListener('mouseleave', () => this.hide());
    }
  }

  static getInstance(element) {
    return SaPopover.instances.get(element) || null;
  }

  static getOrCreateInstance(element, config) {
    return SaPopover.getInstance(element) || new SaPopover(element, config);
  }

  _content() {
    const el = this._element;
    let content = this._config.content;
    if (typeof content === 'function') content = content.call(el);
    if (content === undefined || content === null) {
      content = el.getAttribute('data-bs-content') || el.getAttribute('data-sa-content') || '';
    }
    return content;
  }

  _title() {
    const el = this._element;
    let title = this._config.title;
    if (typeof title === 'function') title = title.call(el);
    return title || el.getAttribute('data-bs-title') || el.getAttribute('data-sa-title') || '';
  }

  _render() {
    this.tip.innerHTML = '';
    const title = this._title();
    if (title) {
      const header = document.createElement('div');
      header.className = 'sa-popover-header popover-header';
      header.textContent = title;
      this.tip.appendChild(header);
    }
    const body = document.createElement('div');
    body.className = 'sa-popover-body popover-body';
    const content = this._content();
    if (content instanceof Node) {
      body.appendChild(content);
    } else if (this._config.html) {
      body.innerHTML = content;
    } else {
      body.textContent = content;
    }
    this.tip.appendChild(body);
  }

  show() {
    if (this._shown) return;
    const showEvent = SuperAdminUI.fire(this._element, 'show.bs.popover');
    if (showEvent.defaultPrevented) return;

    this._render();
    const container =
      this._config.container instanceof Element ? this._config.container : SuperAdminUI.resolve(this._config.container) || document.body;
    container.appendChild(this.tip);
    positionFloating(this.tip, this._element, this._config.placement);
    this.tip.classList.add('show');
    this._shown = true;
    SuperAdminUI.fire(this._element, 'shown.bs.popover');
  }

  hide() {
    if (!this._shown) return;
    const hideEvent = SuperAdminUI.fire(this._element, 'hide.bs.popover');
    if (hideEvent.defaultPrevented) return;
    this.tip.classList.remove('show');
    if (this.tip.parentNode) this.tip.parentNode.removeChild(this.tip);
    this._shown = false;
    SuperAdminUI.fire(this._element, 'hidden.bs.popover');
  }

  toggle() {
    if (this._shown) {
      this.hide();
    } else {
      this.show();
    }
  }

  update() {
    if (this._shown) positionFloating(this.tip, this._element, this._config.placement);
  }

  dispose() {
    this.hide();
    SaPopover.instances.delete(this._element);
  }
}
SaPopover.instances = new WeakMap();

/* -------------------------------------------------------------------- */
/* Bootstrap compatibility layer                                        */
/* -------------------------------------------------------------------- */

class SaModal {
  constructor(element, config) {
    this._element = SuperAdminUI.resolve(element);
    this._config = config || {};
    if (this._element) SaModal.instances.set(this._element, this);
  }

  static getInstance(element) {
    const el = SuperAdminUI.resolve(element);
    return el ? SaModal.instances.get(el) || null : null;
  }

  static getOrCreateInstance(element, config) {
    return SaModal.getInstance(element) || new SaModal(element, config);
  }

  show(relatedTarget) {
    if (this._element) SuperAdminUI._modalOpen(this._element, relatedTarget);
  }

  hide() {
    if (this._element) SuperAdminUI._modalClose(this._element);
  }

  toggle(relatedTarget) {
    if (!this._element) return;
    if (SuperAdminUI.isModalOpen(this._element)) {
      this.hide();
    } else {
      this.show(relatedTarget);
    }
  }

  handleUpdate() {}

  dispose() {
    if (this._element) SaModal.instances.delete(this._element);
  }
}
SaModal.instances = new WeakMap();

class SaTab {
  constructor(element) {
    this._element = SuperAdminUI.resolve(element);
    if (this._element) SaTab.instances.set(this._element, this);
  }

  static getInstance(element) {
    const el = SuperAdminUI.resolve(element);
    return el ? SaTab.instances.get(el) || null : null;
  }

  static getOrCreateInstance(element) {
    return SaTab.getInstance(element) || new SaTab(element);
  }

  show() {
    if (this._element) SuperAdminUI.activateTab(this._element);
  }

  dispose() {
    if (this._element) SaTab.instances.delete(this._element);
  }
}
SaTab.instances = new WeakMap();

class SaCollapse {
  constructor(element, config) {
    this._element = SuperAdminUI.resolve(element);
    this._config = Object.assign({ toggle: true }, config || {});
    if (this._element) {
      SaCollapse.instances.set(this._element, this);
      if (this._config.toggle) this.toggle();
    }
  }

  static getInstance(element) {
    const el = SuperAdminUI.resolve(element);
    return el ? SaCollapse.instances.get(el) || null : null;
  }

  static getOrCreateInstance(element, config) {
    return SaCollapse.getInstance(element) || new SaCollapse(element, Object.assign({ toggle: false }, config || {}));
  }

  _trigger() {
    if (!this._element || !this._element.id) return null;
    return document.querySelector(`[data-sa-target="#${this._element.id}"], [data-bs-target="#${this._element.id}"]`);
  }

  show() {
    if (this._element) SuperAdminUI.collapseShow(this._element, this._trigger());
  }

  hide() {
    if (this._element) SuperAdminUI.collapseHide(this._element, this._trigger());
  }

  toggle() {
    if (this._element) SuperAdminUI.collapseToggle(this._element, this._trigger());
  }

  dispose() {
    if (this._element) SaCollapse.instances.delete(this._element);
  }
}
SaCollapse.instances = new WeakMap();

class SaDropdown {
  constructor(element) {
    this._element = SuperAdminUI.resolve(element);
    if (this._element) SaDropdown.instances.set(this._element, this);
  }

  static getInstance(element) {
    const el = SuperAdminUI.resolve(element);
    return el ? SaDropdown.instances.get(el) || null : null;
  }

  static getOrCreateInstance(element) {
    return SaDropdown.getInstance(element) || new SaDropdown(element);
  }

  _menu() {
    return this._element ? SuperAdminUI.dropdownMenu(this._element) : null;
  }

  show() {
    const menu = this._menu();
    if (menu) SuperAdminUI.openDropdown(menu, this._element);
  }

  hide() {
    const menu = this._menu();
    if (menu) SuperAdminUI.closeDropdown(menu);
  }

  toggle() {
    const menu = this._menu();
    if (!menu) return;
    if (menu.classList.contains('sa-dropdown-open')) {
      this.hide();
    } else {
      this.show();
    }
  }

  dispose() {
    if (this._element) SaDropdown.instances.delete(this._element);
  }
}
SaDropdown.instances = new WeakMap();

if (typeof window.bootstrap === 'undefined') {
  window.bootstrap = {
    Modal: SaModal,
    Popover: SaPopover,
    Tooltip: SaTooltip,
    Tab: SaTab,
    Collapse: SaCollapse,
    Dropdown: SaDropdown,
  };
}

/* -------------------------------------------------------------------- */
/* Boot                                                                 */
/* -------------------------------------------------------------------- */

document.addEventListener('DOMContentLoaded', () => {
  SuperAdminUI.init();
  SuperAdminUI.updateSidebarActive();

  const totop = document.getElementById('totop');
  if (totop) {
    window.addEventListener('scroll', () => {
      totop.classList.toggle('visible', window.scrollY > 300);
    });
    totop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }
});

document.addEventListener('pjax:complete', () => {
  SuperAdminUI.init();
  SuperAdminUI.updateSidebarActive();
});

window.SuperAdminUI = SuperAdminUI;
window.SuperAdminUI.Popover = SaPopover;
window.SuperAdminUI.Tooltip = SaTooltip;
window.SuperAdminUI.Modal = SaModal;
