'use strict';
//IIFE
;(function(d,w,gid) {
	//remove no-js class - very late for preloader
	//d.documentElement.classList.remove('no-js');

	function activateEl(btn, el, cssClass, body, bodyClass) {
		el.classList.add(cssClass);
		body.classList.add(bodyClass);
		btn.setAttribute('aria-expanded', 'true');
		if (btn.id === 'search_toggle') {
			//TODO test on mobile devices
			gid('search_dropdown').children[0].children[0].focus();
		}
		if (btn.id==='message_top_toggle' || btn.id==='message_bottom_toggle') {
			setCookie(btn.getAttribute('data-id'));
			btn.parentNode.remove();
		}
	}

	function deactivateEl(btn, el, cssClass, body, bodyClass) {
		el.classList.remove(cssClass);
		body.classList.remove(bodyClass);
		btn.setAttribute('aria-expanded', 'false');
	}

	function hasClass(el, cssClass) {
		return -1 !== el.className.indexOf(cssClass);
	}

	//toggle CSS class function declaration
	function toggleElListener(elId, btnId, cssClass, body, bodyClass) {
		var btn = gid(btnId);
		var el = gid(elId);
		if (!btn || !el) {
			return;
		}
		btn.addEventListener('click', function() {
			if (hasClass(el, cssClass)) {
				deactivateEl(btn, el, cssClass, body, bodyClass);
			} else {
				activateEl(btn, el, cssClass, body, bodyClass);
			}
		});
		//if clicked not on button and element is active - closing
		body.addEventListener('click', function(e) {
			if (hasClass(el, cssClass) && e.target !== btn && e.target.closest('#' + btnId) !== btn && !e.target.closest('#' + elId)) {
				deactivateEl(btn, el, cssClass, body, bodyClass);
			}
		});
		if (btnId !== 'message_top_toggle' && btnId !== 'message_bottom_toggle') {
			w.addEventListener('scroll', function(e) {
				if (hasClass(el, cssClass)) {
					deactivateEl(btn, el, cssClass, body, bodyClass);
				}
			});
		}
	}

	function hashLinksPrevent(links) {
		for (var i = 0; i < links.length; ++i) {
			links[i].addEventListener('click', function(e) {
				e.preventDefault();
				// https://developer.mozilla.org/en-US/docs/Web/API/Element/closest
				// e.stopPropagation();
			});
		}
	}

	function wrap(el, wrapperClass) {
		for (var i = 0; i < el.length; ++i) {
			var wrapper = d.createElement('div');
			wrapper.setAttribute('class', wrapperClass);

			el[i].parentNode.insertBefore(wrapper, el[i]);
			wrapper.appendChild(el[i]);
		}
	}

	function affix(el) {
		var affix = el.offsetTop;
		w.onscroll = function(e) {
			if (w.pageYOffset >= affix) {
				el.classList.add('affix');
			} else {
				el.classList.remove('affix');
			}
			if (w.pageYOffset===0) {
				el.classList.remove('affix');
			}
			if (this.oldScroll > this.scrollY) {
				el.classList.add('scrolling-up');
				el.classList.remove('scrolling-down');
			} else {
				el.classList.remove('scrolling-up');
				el.classList.add('scrolling-down');
			}
			this.oldScroll = this.scrollY;
		}
	}

	function setCookie(name) {
		var date = new Date();
		date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
		var expires = "expires=" + date.toUTCString();
		d.cookie = name + "=" + '1' + ";" + expires + ";path=/";
	}

	function dummyClickEvent(){
		d.body.dispatchEvent(new Event('click'));
	}

	//on document ready calling function
	d.addEventListener('DOMContentLoaded', function(event) {
		var body = d.body;
		//set togglers for menus
		toggleElListener('nav_top', 'nav_toggle', 'active', body, 'top-menu-active');
		toggleElListener('nav_side', 'nav_side_toggle', 'active', body, 'side-menu-active');
		toggleElListener('search_dropdown', 'search_toggle', 'active', body, 'search-dropdown-active');
		toggleElListener('topline_dropdown', 'topline_dropdown_toggle', 'active', body, 'topline-dropdown-active');
		toggleElListener('dropdown-cart', 'dropdown-cart-toggle', 'active', body, 'cart-dropdown-active');
		toggleElListener('message_top', 'message_top_toggle', 'active', body, 'messagee-top-active');
		toggleElListener('message_bottom', 'message_bottom_toggle', 'active', body, 'messagee-bottom-active');

		//search modal TAB navigation
		var searchCloseBtn = gid('search_modal_close');
		var searchInput = d.querySelector('#search_dropdown .search-field');
		if(searchCloseBtn){
			searchCloseBtn.onclick=function(e){
				var searchToggle=gid('search_toggle');
				if(searchToggle){
					dummyClickEvent();
					searchToggle.focus();
					e.preventDefault();
					e.stopPropagation();
				}
			}
			//cycle tab navigation
			searchCloseBtn.onblur=function(e) {
				if(searchInput){
					searchInput.focus();
				}
			};
		}

		//TAB navigation
		var logo = gid('logo');
		var menuToggler = gid('nav_toggle');
		var navClose = gid('nav_close');
		var firstLink = d.querySelector('.top-menu li:first-child>a');

		//activate menu toggler on logo blur
		if(logo && menuToggler){
			logo.addEventListener('blur', function(event) {
				menuToggler.focus();
			});
		}
		if(navClose && menuToggler){
			menuToggler.addEventListener('click', function(e) {
				navClose.focus();
			});
		}

		d.addEventListener('keydown',function(e) {
			//close all on ESC key click
			if(e.key==='Escape'){
				dummyClickEvent();
			}
			//shift+tab
			if(e.key==='Tab'&&e.shiftKey){
				//close menu on shift+tab on nav close
				if(e.target===navClose){
					dummyClickEvent();
					if(menuToggler){
						menuToggler.focus();
					}
					e.preventDefault();
					e.stopPropagation();
				}
				//shift tab on first menu item
				if(e.target===firstLink&&navClose){
					navClose.focus();
					e.preventDefault();
					e.stopPropagation();
				}
				//search modal
				if(e.target===searchInput&&searchCloseBtn){
					searchCloseBtn.focus();
					e.preventDefault();
					e.stopPropagation();
				}
			}
		});
		if (navClose) {
			//focus on the first menu item on blur
			navClose.onblur=function(e) {
				if(firstLink){
					firstLink.focus();
				}
			};
			navClose.addEventListener('click', function(e) {
				dummyClickEvent();
				if(menuToggler){
					menuToggler.focus();
				}
			});
		}

		//stop linsk with '#' href vaule
		var links = d.querySelectorAll('a[href="#"]');
		hashLinksPrevent(links);
		//sticky header
		// https://www.w3schools.com/howto/howto_js_navbar_sticky.asp
		var headerWrap = gid('header-affix-wrap');
		if (headerWrap) {
			var header = gid('header');
			affix(header);
		}
		// init masonry
		if (typeof (Masonry) !== 'undefined' && typeof (imagesLoaded) !== 'undefined') {
			var grids = d.querySelectorAll('.masonry');
			if (grids.length) {
				var i;
				for (i = 0; i < grids.length; i++) {
					imagesLoaded(grids[i], function(el) {
						new Masonry(el.elements[0], {
							"itemSelector": ".grid-item",
							"columnWidth": ".grid-sizer",
							"percentPosition": true
						});
					});
				}
			}
		}
		//toTop
		var toTop = gid('to-top');
		if (toTop) {
			toTop.addEventListener('click', function(e) {
				e.preventDefault();
				w.scroll({top: 0, left: 0, behavior: 'smooth'});
			});
			w.addEventListener('scroll', function(e) {
				if (w.pageYOffset > 60) {
					toTop.classList.add('visible');
				} else {
					toTop.classList.remove('visible');
				}
			});
		}
		//showing affix header and toTop button if scrolled down
		if(toTop || headerWrap) {
			w.dispatchEvent(new Event('scroll'));
		}
		body.classList.add('dom-loaded');
	});
	w.onload=function() {
		d.body.classList.add('window-loaded');
		//preloader
		var preloader = gid('preloader');
		if (preloader) {
			preloader.classList.add('loaded');
		}
	}
})(document,window,document.getElementById.bind(document));