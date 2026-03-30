/**
 * Super Admin - Tailwind Light Template
 *
 * UI component replacements for Bootstrap JS
 * Provides: dropdown, modal, tab, collapse, tooltip, popover
 */

const SuperAdminUI = {
  /**
   * Initialize all UI components on the page
   */
  init() {
    this.initDropdowns();
    this.initCollapses();
    this.initTabs();
    this.initModals();
    this.initTooltips();
  },

  /**
   * Dropdown - Click to toggle a dropdown menu.
   * Supports data-sa-target="#id" or falls back to nextElementSibling.
   */
  initDropdowns() {
    document.addEventListener('click', (e) => {
      const trigger = e.target.closest('[data-sa-toggle="dropdown"]');

      // Close all open dropdowns that aren't the current one
      document.querySelectorAll('.sa-dropdown-open').forEach((dd) => {
        if (!trigger || !trigger.parentElement.contains(dd)) {
          dd.classList.remove('sa-dropdown-open');
          dd.classList.add('hidden');
        }
      });

      if (trigger) {
        e.preventDefault();
        const targetId = trigger.getAttribute('data-sa-target');
        const menu = targetId ? document.querySelector(targetId) : trigger.nextElementSibling;
        if (menu) {
          menu.classList.toggle('hidden');
          menu.classList.toggle('sa-dropdown-open');
        }
      }
    });
  },

  /**
   * Collapse - Toggle visibility of a target element.
   * Inside #sidebar: accordion behavior (close sibling collapses when opening one).
   */
  initCollapses() {
    document.addEventListener('click', (e) => {
      const trigger = e.target.closest('[data-sa-toggle="collapse"]');
      if (!trigger) return;
      e.preventDefault();
      const targetId = trigger.getAttribute('data-sa-target');
      const target = document.querySelector(targetId);
      if (!target) return;

      const isInSidebar = !!trigger.closest('#sidebar');

      if (isInSidebar) {
        const isCurrentlyHidden = target.classList.contains('hidden');

        // Accordion: close all other sidebar collapses at the same depth level
        const parentLi = trigger.closest('li');
        const parentUl = parentLi ? parentLi.parentElement : null;
        if (parentUl) {
          parentUl.querySelectorAll(':scope > li > button[data-sa-toggle="collapse"]').forEach((btn) => {
            if (btn === trigger) return;
            const otherId = btn.getAttribute('data-sa-target');
            const other = otherId ? document.querySelector(otherId) : null;
            if (other) {
              other.classList.add('hidden');
              const icon = btn.querySelector('.collapse-icon');
              if (icon) icon.classList.remove('rotate-90');
            }
          });
        }

        // Toggle current
        target.classList.toggle('hidden', !isCurrentlyHidden);
        const icon = trigger.querySelector('.collapse-icon');
        if (icon) icon.classList.toggle('rotate-90', isCurrentlyHidden);
      } else {
        target.classList.toggle('hidden');
        const icon = trigger.querySelector('.collapse-icon');
        if (icon) icon.classList.toggle('rotate-90');
      }
    });
  },

  /**
   * Update sidebar active link and auto-expand its parent submenu.
   * Called on initial load and after every PJAX navigation.
   */
  updateSidebarActive() {
    const currentPath = window.location.pathname;

    // Clear all active states
    document.querySelectorAll('#sidebar a.glass-menu-item').forEach((el) => {
      el.classList.remove('glass-menu-item-active');
    });

    // Collapse all submenus
    document.querySelectorAll('#sidebar ul[id^="collapse-"]').forEach((ul) => {
      ul.classList.add('hidden');
      const icon = document.querySelector(`[data-sa-target="#${ul.id}"] .collapse-icon`);
      if (icon) icon.classList.remove('rotate-90');
    });

    // Find best matching link (longest matching href wins)
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

      // Walk up and expand any parent collapse submenus
      let el = bestMatch.parentElement;
      while (el && el.id !== 'menu') {
        if (el.tagName === 'UL' && el.id && el.id.startsWith('collapse-')) {
          el.classList.remove('hidden');
          const icon = document.querySelector(`[data-sa-target="#${el.id}"] .collapse-icon`);
          if (icon) icon.classList.add('rotate-90');
        }
        el = el.parentElement;
      }
    }
  },

  /**
   * Tabs - Switch between tab panels
   */
  initTabs() {
    document.addEventListener('click', (e) => {
      const tab = e.target.closest('[data-sa-toggle="tab"]');
      if (tab) {
        e.preventDefault();
        const targetId = tab.getAttribute('data-sa-target');
        const tabGroup = tab.closest('[data-sa-tabs]');

        if (tabGroup) {
          // Deactivate all tabs in group
          tabGroup.querySelectorAll('[data-sa-toggle="tab"]').forEach((t) => {
            t.classList.remove('glass-tab-active');
          });

          // Hide all panels in group
          const panelContainer = document.querySelector(tabGroup.getAttribute('data-sa-tabs'));
          if (panelContainer) {
            panelContainer.querySelectorAll('[data-sa-tab-panel]').forEach((p) => {
              p.classList.add('hidden');
            });
          }
        }

        // Activate clicked tab
        tab.classList.add('glass-tab-active');

        // Show target panel
        const panel = document.querySelector(targetId);
        if (panel) panel.classList.remove('hidden');
      }
    });
  },

  /**
   * Modal - Show/hide modal dialogs
   * Both .glass-modal-backdrop and .glass-modal are shown/hidden together.
   * A trigger may target either the modal id or the backdrop id.
   */
  _modalOpen(targetId) {
    // Normalise: remove -backdrop suffix to get the modal id
    const modalId = targetId.replace(/-backdrop$/, '');
    const backdropId = modalId + '-backdrop';
    const modal = document.querySelector(modalId);
    const backdrop = document.querySelector(backdropId);
    if (modal) modal.classList.remove('hidden');
    if (backdrop) backdrop.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  },

  _modalClose(el) {
    // el can be inside .glass-modal or .glass-modal-backdrop
    const container = el.closest('.glass-modal') || el.closest('.glass-modal-backdrop');
    if (!container) return;
    const baseId = container.id ? '#' + container.id.replace(/-backdrop$/, '') : null;
    if (baseId) {
      const modal = document.querySelector(baseId);
      const backdrop = document.querySelector(baseId + '-backdrop');
      if (modal) modal.classList.add('hidden');
      if (backdrop) backdrop.classList.add('hidden');
    } else {
      container.classList.add('hidden');
    }
    document.body.classList.remove('overflow-hidden');
  },

  initModals() {
    // Open modal
    document.addEventListener('click', (e) => {
      const trigger = e.target.closest('[data-sa-toggle="modal"]');
      if (trigger) {
        e.preventDefault();
        const targetId = trigger.getAttribute('data-sa-target');
        if (targetId) this._modalOpen(targetId);
      }
    });

    // Close: click the .glass-modal flex background (outside dialog)
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('glass-modal')) {
        this._modalClose(e.target);
      }
    });

    // Close: click a dismiss button
    document.addEventListener('click', (e) => {
      const closeBtn = e.target.closest('[data-sa-dismiss="modal"]');
      if (closeBtn) this._modalClose(closeBtn);
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        document.querySelectorAll('.glass-modal:not(.hidden)').forEach((m) => {
          this._modalClose(m);
        });
      }
    });
  },

  /**
   * Tooltips - Show tooltip on hover
   */
  initTooltips() {
    document.querySelectorAll('[data-sa-tooltip]').forEach((el) => {
      const text = el.getAttribute('data-sa-tooltip');
      const tooltip = document.createElement('div');
      tooltip.className = 'absolute z-50 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-lg pointer-events-none opacity-0 transition-opacity duration-200 whitespace-nowrap';
      tooltip.textContent = text;
      el.classList.add('relative');
      el.appendChild(tooltip);

      el.addEventListener('mouseenter', () => {
        tooltip.classList.remove('opacity-0');
        tooltip.classList.add('opacity-100');
        tooltip.style.bottom = '100%';
        tooltip.style.left = '50%';
        tooltip.style.transform = 'translateX(-50%) translateY(-4px)';
      });

      el.addEventListener('mouseleave', () => {
        tooltip.classList.remove('opacity-100');
        tooltip.classList.add('opacity-0');
      });
    });
  },
};

// Auto-init on DOM ready and after PJAX loads
document.addEventListener('DOMContentLoaded', () => {
  SuperAdminUI.init();
  SuperAdminUI.updateSidebarActive();

  // Back-to-top button
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

// Export for global access
window.SuperAdminUI = SuperAdminUI;
