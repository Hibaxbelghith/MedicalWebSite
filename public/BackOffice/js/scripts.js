/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

document.addEventListener('DOMContentLoaded', function () {
    // Vérifie que les variables sont définies
    if (typeof totalRendezVous !== 'undefined' && typeof chartData !== 'undefined' && typeof chartLabels !== 'undefined') {
        var ctx = document.getElementById('myChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    datasets: [{
                        type: 'bar',
                        label: totalRendezVous + ' Rendez-vous',
                        data: chartData,
                        borderWidth: 1,
                        backgroundColor: '#9BD0F5',
                        borderColor: '#9BD0F5',
                    }],
                    labels: chartLabels,
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }
});


