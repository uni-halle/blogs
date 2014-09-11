(function ($) {
$(function () {

/* ----- Auth popup ----- */
$("#wdgpo-gplus_authenticate").click(function () {
	var url = $("#wdgpo-gplus_auth_url").val();
	if (!url) return false;
	var googleLogin = window.open(url, "google_login", "scrollbars=no,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
	var gTimer = setInterval(function () {
		try {
			if (googleLogin.location.hostname == window.location.hostname) {
				clearInterval(gTimer);
				googleLogin.close();
				window.location.reload();
			}
		} catch (e) {}
	}, 300);
	return false;
});

/* ----- De-Auth link ----- */
$("#wdgpo-gplus_deauthenticate").click(function () {
	if (!confirm("Are you sure?")) return false;
	$.post(ajaxurl, {"action": "wdgpo_gplus_deauthenticate"}, function () {
		window.location.reload();
	});
	return false;
});

/* ----- Test import ----- */
$("#wdgpo-gplus_test_import").click(function () {
	var me = $(this);
	if ($("#wdgpo-test_import-result").length) $("#wdgpo-test_import-result").remove();
	me.parents('p').append('<span class="wdgpo-working"> ... working ...</span>');
	$.post(ajaxurl, {"action": "wdgpo_gplus_test_import"}, function (data) {
		var html = '<div id="wdgpo-test_import-result">';
		var results = [];
		try {
			results = data.results;
		} catch (e) {}
		$(".wdgpo-working").remove();
		$.each(results, function (idx, result) {
			var status = false;
			var title = false;
			try {
				status = parseInt(result.status);
				title = result.title;
			} catch (e) {}
			if (status) html += title + ' successfuly reached<br />';
			else html += ' <b>There was an error reaching your page.</b><br />';
		});
		html += '</div>';
		me.parents('p').append(html);
	});
});

/* ----- Multiple imports toggle ----- */
if ($("#wdgpo-multiple_import-action").length) $("#wdgpo-multiple_import-action").click(function () {
	$("#wdgpo-multiple_import").show();
	$("#wdgpo-multiple_import-action").parents('p').remove();
	return false;
});

/* ----- Multiple import items replication ----- */
$("#wdgpo-multi_import-add_one").click(function () {
	var $li = $("#wdgpo-multiple_import ul li:last").clone(true);
	$li.find("input:text").val('');
	$("#wdgpo-multiple_import ul").append($li);
	return false;
});

/* ----- Multiple import items removal ----- */
$(".wdgpo-multiple_import-remove_item").live('click', function () {
	if ($("#wdgpo-multiple_import ul li").length == 1) { // Don't remove the last one - clear it instead
		$(this).parents('li').find("input:text").val('');
		return false;
	}
	$(this).parents('li').remove();
	return false;
});

/* ----- Manual import ----- */
$("#wdgpo-gplus_import_now").click(function () {
	var me = $(this);
	if ($("#wdgpo-gplus_import_now-result").length) $("#wdgpo-gplus_import_now-result").remove();
	me.parents('p').append('<span class="wdgpo-working"> ... working ...</span>');
	$.post(ajaxurl, {"action": "wdgpo_gplus_import_now"}, function (data) {
		var html = '<div id="wdgpo-gplus_import_now-result">';
		var results = [];
		try {
			results = data.results;
		} catch (e) {}
		$(".wdgpo-working").remove();
		$.each(results, function (idx, result) {
			var status = false;
			var title = false;
			var items = 0;
			try {
				status = parseInt(result.status);
				title = result.title;
				items = parseInt(result.items);
			} catch (e) {}
			if (status) html += items + ' items successfuly imported from <i>' + title + '</i>.<br />';
			else html += '<b>There was an error reaching your page.</b><br />';
		});
		html += '</div>';
		me.parents('p').append(html);
	});
});

/* ----- Log toggling ----- */
$("#wdgpo-toggle_log").on("click", function () {
	if ($("#wdgpo-log_container").is(":visible")) $("#wdgpo-log_container").hide();
	else $("#wdgpo-log_container").show();
	var off = $("#wdgpo-toggle_log").text();
	$("#wdgpo-toggle_log").text($("#wdgpo-toggle_log").attr("data-off_label"));
	$("#wdgpo-toggle_log").attr("data-off_label", off);
	return false;
});

$("#wdgpo-clear_log").on("click", function () {
	$(this).text('Working...');
	$.post(ajaxurl, {"action": "wdgpo_gplus_clear_log"}, function () {
		window.location.reload();
	});
});


});
})(jQuery);