window.addEventListener("load", async function (event) {
    let dayReservation;
    let zoneR = document.getElementById('zoneReserve');
    let timeR = document.getElementById('timeReserve');
    let btn = document.getElementById('btn_submit');
    let containerInformations = document.getElementById('container-info');
    let unavailableDates = [];
    let unavailableReserves = [];

    await fetch('/wp-admin/admin-ajax.php?action=send_reservations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Cache-Control': 'no-cache',
        }
    }).then(response => {
        return response.json();
    }).then(data => {
        let reserves = data.data
        reserves.forEach(reserve => {
            unavailableReserves.push({ 'date': reserve.date, 'time': reserve.time, 'zone': reserve.area })
        });
    })

    var availableDays = await fetch('/wp-admin/admin-ajax.php?action=send_available_days', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Cache-Control': 'no-cache',
        }
    }).then(response => {
        return response.json();
    }).then(data => {
        let datos = data.data
        const result = datos.reduce((accumulator, element, index) => ({
            ...accumulator,
            [element.id]: element.id
        }), {});
        return result
    })

    $(function () {
        var $dR = $("#dateReserve");
        var $zR = $("#zoneReserve");
        var $tR = $("#timeReserve");

        $dR.datepicker({
            changeYear: true,
            changeMonth: true,
            minDate: 0,
            dateFormat: "mm-dd-yy",
            yearRange: "-100:+20",
            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            beforeShowDay: availableDaysF
        });

        $dR.change(function () {
            btn.disabled = true;
            var $dateD = $dR.val();

            const nombreDelDiaSegunFecha = fecha => [
                'Sun',
                'Mon',
                'Tue',
                'Wed',
                'Thu',
                'Fri',
                'Sat',
            ][new Date(fecha).getDay()];

            let day = nombreDelDiaSegunFecha($dateD);

            fetch('/wp-admin/admin-ajax.php?action=send_available_zones', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cache-Control': 'no-cache',
                },
                body: new URLSearchParams({
                    day: day
                })
            }).then(response => {
                return response.json();
            }).then(data => {
                let zones = data.data;
                dayReservation = day;
                zoneR.innerHTML = '';

                if (zones.length != 0) {
                    zoneR.disabled = false;
                    zoneR.innerHTML += `<option selected hidden>Selecione una zona</option>`
                    timeR.innerHTML += `<option selected hidden>Selecione una zona</option>`
                    zones.forEach(z => {
                        zoneR.innerHTML += `<option value="${z.id}">${z.zone}</option>`
                    })
                } else {
                    zoneR.disabled = true;
                    timeR.disabled = true;
                    zoneR.innerHTML = `<option selected hidden>Sin zonas disponibles</option>`
                    timeR.innerHTML = `<option selected hidden>Sin horas disponibles</option>`
                }
            })
        });

        $zR.change(function () {
            btn.disabled = true;
            let zoneId = $zR.val();

            fetch('/wp-admin/admin-ajax.php?action=send_available_times', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cache-Control': 'no-cache',
                },
                body: new URLSearchParams({
                    day: dayReservation,
                    zone: zoneId
                })
            }).then(response => {
                return response.json();
            }).then(data => {
                let times = data.data

                if (times.length > 0) {
                    timeR.disabled = false;
                    zoneName = zoneR.options[zoneR.selectedIndex].text
                    timeR.innerHTML = '';
                    timeR.innerHTML += `<option selected hidden>Selecione un horario</option>`
                    let timesAvailables = [];
                    unavailableReserves.forEach(r => {
                        times.forEach(t => {
                            if(r.time == t.time && r.date == $dR.val() && zoneName == r.zone){
                                timesAvailables.push(t)
                            }
                        })
                    })
                    const timeAvailables = times.filter(d => !timesAvailables.includes(d));
                    if(timeAvailables.length > 0){
                        timeAvailables.forEach  (time => {
                            timeR.innerHTML += `<option value="${time.time}">${time.time}</option>`
                        })
                    }else {
                        timeR.disabled = true;
                        timeR.innerHTML = `<option selected hidden>Sin horarios disponibles</option>`
                    }
                } else {
                    timeR.disabled = true;
                    timeR.innerHTML = `<option selected hidden>Sin horarios disponibles</option>`
                }
            })
        })

        $tR.change(function () {
            btn.disabled = false;
        })

        var inputPhone = document.querySelector("#input-phone");
        window.intlTelInput(inputPhone, ({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            autoHideDialCode: true,
            autoPlaceholder: "ON",
            dropdownContainer: document.body,
            formatOnDisplay: true,
            hiddenInput: "full_number",
            initialCountry: "ES",
            nationalMode: true,
            placeholderNumberType: "MOBILE",
            preferredCountries: ['ES'],
            separateDialCode: true
        }));

        let formReservation = document.getElementById('formReservation');
        formReservation.addEventListener('submit', e => {
            btn.disabled = true;
            e.preventDefault();

            var $persons = $("#persons").val();
            var $name = $("#name").val();
            var $email = $("#email").val();
            let $cbox1Value = $("#cbox1").val();
            let $cbox2Value = $("#cbox2").val();
            let $cbox3Value = $("#cbox3").val();
            let phoneComplete = window.intlTelInputGlobals.getInstance(inputPhone).getNumber();

            fetch('/wp-admin/admin-ajax.php?action=send_reservation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cache-Control': 'no-cache',
                },
                body: new URLSearchParams({
                    name: $name,
                    phone: phoneComplete,
                    email: $email,
                    persons: $persons,
                    date: $dR.val(),
                    area: zoneR.options[zoneR.selectedIndex].text,
                    time: timeR.value,
                    cbox1: $cbox1Value,
                    cbox2: $cbox2Value,
                    cbox3: $cbox3Value,
                })
            }).then(response => {
                return response.json();
            }).then(data => {
                location.reload()
            })

        })
    });

    function availableDaysF(date) {

        var day = date.getDay() == 0 ? 7 : date.getDay();
        var dayN = availableDays[day];

        dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
        if ($.inArray(dmy, unavailableDates) != -1) {
            return [false, "", "Unavailable"];
        } else if (day != dayN)
            return [false, "closed", "Closed on Day"]
        else
            return [true, "", ""]
    }

    await fetch('/wp-admin/admin-ajax.php?action=send_restaurant_info', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Cache-Control': 'no-cache',
        }
    }).then(response => {
        return response.json();
    }).then(data => {
        let infos = data.data;
        containerInformations.innerHTML = '';

        infos.forEach(i => {
            containerInformations.innerHTML += `
                <h3 class="">${i.title}</h3>
                <div>
                    <p>
                    ${nl2br(i.body)}
                    </p>
                </div>
            `;
        })

        $("#container-info").accordion();
    })

    function nl2br(str, is_xhtml) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

    document.getElementById('timeReserve').addEventListener('change', (e) => {
        let hour = document.getElementById('timeReserve').value;

        if (hour != null) {
            document.getElementById('continue').classList.remove('reservationNextHidden');
        };
    });

    document.getElementById('continue').addEventListener('click', (e) => {
        if (document.getElementById('continue').classList[1] == null) {
            let people = document.getElementById('persons').value;
            let date = document.getElementById('dateReserve').value;
            let zone = $('#zoneReserve option:selected').text();
            let hour = document.getElementById('timeReserve').value;

            if (hour <= 12) {
                hour = hour + ' p.m.';
            } else {
                hour = hour + ' a.m';
            };
            toggleClassList();

            document.getElementById('detailDate').innerHTML = date + ', ' + hour + '.';
            document.getElementById('detailPeople').innerHTML = people + ' personas, ' + zone + '.';
        }
    });

    document.getElementById('return').addEventListener('click', (e) => {
        toggleClassList();
    });

    var nodes = document.getElementById('container-info').children;
    for(var i=0; i<nodes.length; i++) {
        nodes[i].className = '';
        nodes[i].className = 'titleBox';
    };
});

function toggleClassList () {
    let box1 = document.getElementById('containerFormOne');
    let box2 = document.getElementById('containerFormTwo');

    if (box1.classList[1] == undefined) {
        box1.classList.add('hidden');
        box2.classList.remove('hidden');
    } else {
        box1.classList.remove('hidden');
        box2.classList.add('hidden');
    };
};
