jQuery(document).ready(function ($a) {
    var href = drupalSettings.path.baseUrl + '/get-time';
    $a.ajax({
        url: href,
        method: 'GET',
        dataType: "json",
        async: false,
        success: function (result) {
            var time = '<h2>' + result.data.time + '</h2>';
            $a(".site-location-time").html(time);
            $a(".site-location-date").html(result.data.date);
        }
    });
});
