/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdpol_data, ajaxurl*/
var gdpol_admin_core;

;(function($, window, document, undefined) {
    gdpol_admin_core = {
        storage: {
            url: ""
        },
        init: function() {
            if (gdpol_data.page === "tools") {
                gdpol_admin_core.tools.init();
            }

            if (gdpol_data.page === "polls") {
                gdpol_admin_core.dialogs.polls();
                gdpol_admin_core.polls.init();
            }

            if (gdpol_data.page === "votes") {
                gdpol_admin_core.dialogs.votes();
                gdpol_admin_core.votes.init();
            }
        },
        dialogs: {
            classes: function(extra) {
                var cls = "wp-dialog d4p-dialog gdpol-modal-dialog";

                if (extra !== "") {
                    cls+= " " + extra;
                }

                return cls;
            },
            defaults: function() {
                return {
                    width: 480,
                    height: "auto",
                    minHeight: 24,
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    closeOnEscape: false,
                    zIndex: 300000,
                    open: function() {
                        $(".gdpol-button-focus").focus();
                    }
                };
            },
            icons: function(id) {
                $(id).next().find(".ui-dialog-buttonset button").each(function(){
                    var icon = $(this).data("icon");

                    if (icon !== "") {
                        $(this).find("span.ui-button-text").prepend(gdpol_data["button_icon_" + icon]);
                    }
                });
            },
            polls: function() {
                var dlg_delete = $.extend({}, gdpol_admin_core.dialogs.defaults(), {
                    dialogClass: gdpol_admin_core.dialogs.classes("gdpol-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdpol-polls-del-delete",
                            class: "gdpol-dialog-button-delete",
                            text: gdpol_data.dialog_button_delete,
                            data: { icon: "delete" },
                            click: function() {
                                window.location.href = gdpol_admin_core.storage.url;
                            }
                        },
                        {
                            id: "gdpol-delete-del-cancel",
                            class: "gdpol-dialog-button-cancel gdpol-button-focus",
                            text: gdpol_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdpol-dialog-polls-delete").wpdialog("close");
                            }
                        }
                    ]
                }), 
                dlg_disable = $.extend({}, gdpol_admin_core.dialogs.defaults(), {
                    dialogClass: gdpol_admin_core.dialogs.classes("gdpol-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdpol-polls-dis-disable",
                            class: "gdpol-dialog-button-delete",
                            text: gdpol_data.dialog_button_disable,
                            data: { icon: "disable" },
                            click: function() {
                                window.location.href = gdpol_admin_core.storage.url;
                            }
                        },
                        {
                            id: "gdpol-delete-dis-cancel",
                            class: "gdpol-dialog-button-cancel gdpol-button-focus",
                            text: gdpol_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdpol-dialog-polls-disable").wpdialog("close");
                            }
                        }
                    ]
                }), 
                dlg_empty = $.extend({}, gdpol_admin_core.dialogs.defaults(), {
                    dialogClass: gdpol_admin_core.dialogs.classes("gdpol-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdpol-polls-emp-disable",
                            class: "gdpol-dialog-button-delete",
                            text: gdpol_data.dialog_button_empty,
                            data: { icon: "empty" },
                            click: function() {
                                window.location.href = gdpol_admin_core.storage.url;
                            }
                        },
                        {
                            id: "gdpol-delete-emp-cancel",
                            class: "gdpol-dialog-button-cancel gdpol-button-focus",
                            text: gdpol_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdpol-dialog-polls-empty").wpdialog("close");
                            }
                        }
                    ]
                });

                $("#gdpol-dialog-polls-delete").wpdialog(dlg_delete);
                $("#gdpol-dialog-polls-disable").wpdialog(dlg_disable);
                $("#gdpol-dialog-polls-empty").wpdialog(dlg_empty);

                gdpol_admin_core.dialogs.icons("#gdpol-dialog-polls-delete");
                gdpol_admin_core.dialogs.icons("#gdpol-dialog-polls-disable");
                gdpol_admin_core.dialogs.icons("#gdpol-dialog-polls-empty");
            },
            votes: function() {
                var dlg_delete = $.extend({}, gdpol_admin_core.dialogs.defaults(), {
                    dialogClass: gdpol_admin_core.dialogs.classes("gdpol-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdpol-polls-del-delete",
                            class: "gdpol-dialog-button-delete",
                            text: gdpol_data.dialog_button_delete,
                            data: { icon: "delete" },
                            click: function() {
                                window.location.href = gdpol_admin_core.storage.url;
                            }
                        },
                        {
                            id: "gdpol-delete-del-cancel",
                            class: "gdpol-dialog-button-cancel gdpol-button-focus",
                            text: gdpol_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdpol-dialog-votes-delete").wpdialog("close");
                            }
                        }
                    ]
                });

                $("#gdpol-dialog-votes-delete").wpdialog(dlg_delete);

                gdpol_admin_core.dialogs.icons("#gdpol-dialog-votes-delete");
            }
        },
        polls: {
            init: function() {
                $(".gdpol-button-disable-poll").click(function(e){
                    e.preventDefault();

                    gdpol_admin_core.storage.url = $(this).attr("href");

                    $("#gdpol-dialog-polls-disable").wpdialog("open");
                });

                $(".gdpol-button-delete-poll").click(function(e){
                    e.preventDefault();

                    gdpol_admin_core.storage.url = $(this).attr("href");

                    $("#gdpol-dialog-polls-delete").wpdialog("open");
                });

                $(".gdpol-button-empty-poll").click(function(e){
                    e.preventDefault();

                    gdpol_admin_core.storage.url = $(this).attr("href");

                    $("#gdpol-dialog-polls-empty").wpdialog("open");
                });
            }
        },
        votes: {
            init: function() {
                $(".gdpol-button-delete-vote").click(function(e){
                    e.preventDefault();

                    gdpol_admin_core.storage.url = $(this).attr("href");

                    $("#gdpol-dialog-votes-delete").wpdialog("open");
                });
            }
        },
        tools: {
            init: function() {
                if (gdpol_data.panel === "export") {
                    gdpol_admin_core.tools.export();
                }
            },
            export: function() {
                $("#gdpol-tool-export").click(function(e){
                    e.preventDefault();

                    window.location = $("#gdpol-export-url").val();
                });
            }
        }
    };

    gdpol_admin_core.init();
})(jQuery, window, document);
