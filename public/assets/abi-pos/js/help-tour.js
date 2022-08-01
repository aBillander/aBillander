$(document).ready(function() {
    var tour = new Tour({
        name: 'tour',
        steps: [],
        template:
            "<div class='popover tour'><div class='arrow'></div><h3 class='popover-title text-bold'></h3><div class='popover-content'></div><div class='popover-navigation'><button class='btn btn-success btn-sm' data-role='prev'>« " +
            LANG.prev +
            "</button>&nbsp;<button class='btn btn-success btn-sm' data-role='next'>" +
            LANG.next +
            " »</button><button class='btn btn-default btn-sm' data-role='end'>" +
            LANG.end_tour +
            '</button></div></div>',
        orphan: true,
        backdrop: true,
    });
    tour.addSteps([
        {
            element: '#tour_step1',
            title: LANG.tour_step1_title,
            content: LANG.tour_step1_content,
        },
        {
            element: '#tour_step2',
            title: LANG.tour_step2_title,
            content: LANG.tour_step2_content,
            onShow: function(tour) {
                if (
                    !$('#tour_step2')
                        .closest('li')
                        .hasClass('active')
                ) {
                    $('#tour_step2').trigger('click');
                }
            },
        },
        {
            element: '#tour_step3',
            title: LANG.tour_step3_title,
            content: LANG.tour_step3_content_modified,
            onShow: function(tour) {
                if (
                    !$('#tour_step3')
                        .hasClass('active')
                ) {
                    $('#tour_step3 a:first').trigger('click');
                }
            },
        },
        {
            element: '#tour_step4',
            title: LANG.tour_step4_title,
            content: LANG.tour_step4_content,
            onShow: function(tour) {
                if (
                    !$('#tour_step4')
                        .hasClass('active')
                ) {
                    $('#tour_step4 a:first').trigger('click');
                }
            },
        },
        {
            element: '#tour_step5',
            title: LANG.tour_step5_title,
            content: LANG.tour_step5_content_modified,
            onShow: function(tour) {
                if (
                    !$('#tour_step5')
                        .hasClass('active')
                ) {
                    $('#tour_step5 a:first').trigger('click');
                }
            },
        },
        {
            element: '#tour_step6',
            title: LANG.tour_step6_title,
            content: LANG.tour_step6_content,
            onShow: function(tour) {
                if (
                    !$('#tour_step6')
                        .hasClass('active')
                ) {
                    $('#tour_step6 a:first').trigger('click');
                }
            },
        },
        {
            element: '#tour_step7',
            title: LANG.tour_step7_title,
            content: LANG.tour_step7_content,
            onShow: function(tour) {
                if (
                    !$('#tour_step7')
                        .hasClass('active')
                ) {
                    $('#tour_step7 :first').trigger('click');
                }
            },
        },
        {
            element: '#tour_step8',
            title: LANG.tour_step8_title,
            content: LANG.tour_step8_content,
            onShow: function(tour) {
                if (
                    !$('#tour_step8')
                        .hasClass('active')
                ) {
                    $('#tour_step8 a:first').trigger('click');
                }
            },
        },
    ]);
    $('#start_tour').click(function() {
        tour.init();
        tour.restart();
    });

    if ($('#start_tour').length > 0 && localStorage.getItem('upos_app_tour_shown') !== 'true') {
        $('#start_tour').trigger('click');
        localStorage.setItem('upos_app_tour_shown', 'true');
    }
});
