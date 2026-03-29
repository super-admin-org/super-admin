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
   * Collapse - Toggle visibility of a target element
   */
  initCollapses() {
    document.addEventListener('click', (e) => {
      const trigger = e.target.closest('[data-sa-toggle="collapse"]');
      if (trigger) {
        e.preventDefault();
        const targetId = trigger.getAttribute('data-sa-target');
        const target = document.querySelector(targetId);
        if (target) {
          target.classList.toggle('hidden');
          // Toggle icon rotation
          const icon = trigger.querySelector('.collapse-icon');
          if (icon) icon.classList.toggle('rotate-90');
        }
      }
    });
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
   */
  initModals() {
    // Open modal
    document.addEventListener('click', (e) => {
      const trigger = e.target.closest('[data-sa-toggle="modal"]');
      if (trigger) {
        e.preventDefault();
        const targetId = trigger.getAttribute('data-sa-target');
        const modal = document.querySelector(targetId);
        if (modal) {
          modal.classList.remove('hidden');
          document.body.classList.add('overflow-hidden');
        }
      }
    });

    // Close modal (click backdrop or close button)
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('glass-modal-backdrop')) {
        e.target.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }

      const closeBtn = e.target.closest('[data-sa-dismiss="modal"]');
      if (closeBtn) {
        const modal = closeBtn.closest('.glass-modal-backdrop');
        if (modal) {
          modal.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
        }
      }
    });

    // Close modal on Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        document.querySelectorAll('.glass-modal-backdrop:not(.hidden)').forEach((m) => {
          m.classList.add('hidden');
        });
        document.body.classList.remove('overflow-hidden');
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
document.addEventListener('DOMContentLoaded', () => SuperAdminUI.init());
document.addEventListener('pjax:complete', () => SuperAdminUI.init());

// Export for global access
window.SuperAdminUI = SuperAdminUI;
