var DashboardController = (function ($) {
    return {
        createUpdate: function () {
            DashboardController.Summary.init();
        },
        stateWiseRegistraion: function (categories, data) {
            Highcharts.chart('an-g1', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'State Wise Total Registration'
                },
                subtitle: {
                    text: 'Source: High Court of Himachal Pradesh'
                },
                xAxis: {
                    categories: categories,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Registration (in count)'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Registrations',
                    data: data
            
                }]
            });
        },
        stateWiseProfileComplete: function (categories, data) {
            Highcharts.chart('an-g2', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'State Wise Total Profile Completed'
                },
                subtitle: {
                    text: 'Source: High Court of Himachal Pradesh'
                },
                xAxis: {
                    categories: categories,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Profile Completed'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Registrations',
                    data: data
            
                }]
            });
        },
        genderWiseRegistraion: function (data) {
            Highcharts.chart('GenderWise', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Gender Wise Applicant'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Gender',
                    colorByPoint: true,
                    data: data
                }]
            });
        },
        categoryWiseRegistraion: function (data) {
            Highcharts.chart('SocialWise', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Social Category Wise Applicant'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Social Category',
                    colorByPoint: true,
                    data: data
                }]
            });
        },
        adTotalActive: function (data) {
            Highcharts.chart('adTotalActive', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'No of Advertisement Vs Active Advertisement'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Advertisement',
                    colorByPoint: true,
                    data: data
                }]
            });
        },
        advertismentWiseCount: function (advt, data) {
            Highcharts.chart('AdvertismentWiseCount', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Advertisement Wise Application Status'
                },
                subtitle: {
                    text: 'Source: High Court of Himachal Pradesh'
                },
                xAxis: {
                    categories: advt,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Applicant'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: data
            });
        },
    };
}(jQuery));

DashboardController.Summary = (function ($) {
    var attachEvents = function () {

    };
    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));