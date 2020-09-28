'use strict';
(function ($, api) {

	api.bind('ready', function () {

		//redirect to appropriate pages
		api.section( 'section_blog', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					wp.customize.previewer.previewUrl.set( exsCustomizerObject.blogUrl );
				}
			} );
		} );
		api.section( 'section_blog_post', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					wp.customize.previewer.previewUrl.set( exsCustomizerObject.postUrl );
				}
			} );
		} );
		api.section( 'section_exs_woocommerce_layout', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					wp.customize.previewer.previewUrl.set( exsCustomizerObject.shopUrl );
				}
			} );
		} );
		api.section( 'section_exs_woocommerce_products', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					wp.customize.previewer.previewUrl.set( exsCustomizerObject.shopUrl );
				}
			} );
		} );

		var previewWrap = document.getElementById('customize-preview');

		//////////
		//colors//
		//////////
		//set CSS variables dynamic
		var colors = [
			'colorLight',
			'colorFont',
			'colorFontMuted',
			'colorBackground',
			'colorBorder',
			'colorDark',
			'colorDarkMuted',
			'colorMain',
			'colorMain2'
		];
		colors.forEach(function (color) {
			api(color, function (control) {
				control.bind(function () {
					if(!control) {
						return;
					}
					//set style on iframe root element
					previewWrap.firstChild.contentWindow.document.documentElement.style.setProperty('--'+color, control.get());
				})
			});
		});

		///////////
		//buttons//
		///////////
			//checkboxes
			// 'buttons_uppercase'
			// 'buttons_bold'
			// 'buttons_colormain'
			// 'buttons_outline'
		var buttonCheckboxes = [
			{ 'buttons_uppercase': 'btns-uppercase' },
			{ 'buttons_bold': 'btns-bold' },
			{ 'buttons_colormain': 'btns-colormain' },
			{ 'buttons_outline': 'btns-outline' },
		];
		buttonCheckboxes.forEach(function (obj, i) {
			for (var prop in obj) {
				api(prop, function (control) {
					control.bind(function () {
						if (!control) {
							return;
						}
						if (control.get()) {
							previewWrap.firstChild.contentWindow.document.body.classList.add(buttonCheckboxes[i][prop]);
						} else {
							previewWrap.firstChild.contentWindow.document.body.classList.remove(buttonCheckboxes[i][prop]);
						}
					})
				});
			}
		});
		// 'buttons_radius'
		api('buttons_radius', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var val = control.get();
				previewWrap.firstChild.contentWindow.document.body.classList.remove('btns-rounded');
				previewWrap.firstChild.contentWindow.document.body.classList.remove('btns-round');
				if (control.get()) {
					previewWrap.firstChild.contentWindow.document.body.classList.add(val);
				}
			})
		});
		// 'buttons_fs'
		api('buttons_fs', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var val = control.get();
				var btnFsClasses = [
					'b-fs-9',
					'b-fs-10',
					'b-fs-11',
					'b-fs-12',
					'b-fs-13',
					'b-fs-14',
					'b-fs-15',
					'b-fs-16',
					'b-fs-17',
					'b-fs-18',
					'b-fs-19',
					'b-fs-20',
					'b-fs-21',
					'b-fs-22'
				];
				btnFsClasses.forEach(function (val) {
					previewWrap.firstChild.contentWindow.document.body.classList.remove(val);
				});
				if (control.get()) {
					previewWrap.firstChild.contentWindow.document.body.classList.add('b-'+val);
				}
			})
		});

		// 'meta icons color'
		api('color_meta_icons', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var val = control.get();
				var btnFsClasses = [
					'meta-icons-main',
					'meta-icons-main2',
					'meta-icons-border',
					'meta-icons-dark',
					'meta-icons-dark-muted'
				];
				btnFsClasses.forEach(function (val) {
					previewWrap.firstChild.contentWindow.document.body.classList.remove(val);
				});
				if (control.get()) {
					previewWrap.firstChild.contentWindow.document.body.classList.add(val);
				}
			})
		});

		///////////
		//presets//
		///////////
		//these settings are set in options.php file
		var presets = [
			{
				"header": "1",
				"header_fluid": "",
				"logo": "1",
				"skin": "1",
			},
			{
				"header": "2",
				"header_fluid": "1",
				"logo": "2",
				"skin": "2",
			}
		];

		api('preset', function (preset) {
			//bind function on change preset value 
			preset.bind(function () {
				if (!preset) {
					return;
				}

				var presetNum = parseInt(preset.get(), 10) - 1;
				for (var key in presets[presetNum]) {
					api(key, function (control) {
						control.set(presets[presetNum][key]);
					});
				}

			})
		});

		//google font
		// api( 'font_body', function( fontName ) {
		// 	fontName.bind( function(){
		// 		if(!fontName) {
		// 			return;
		// 		}

		// 		var font = exsGoogleFonts[fontName.get()];
		// 		api( 'font_body_variants', function( control ) {
		// 			console.log(control);
		// 		});
		// 		// console.log(font);
		// 	});
		// })
	});

})(jQuery, wp.customize);
