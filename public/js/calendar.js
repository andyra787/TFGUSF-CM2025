let formulario = document.querySelector('#formNuevo');
let calendar;
let urlCitas;

document.addEventListener('DOMContentLoaded', function() {
    
    let doctorSelect = document.getElementById('doctorSelect');

    if(doctorSelect.value == 'nn') {
        urlCitas = '/citas/ver?medico_id=1';
        doctorSelect.value = 1;
    }else{
        urlCitas = '/citas/ver?medico_id=' + doctorSelect.value;
    }


    doctorSelect.addEventListener('change', function() {
        var medico_id = this.value;

        var eventSource = calendar.getEventSourceById('mySource');
        if (eventSource) {
            eventSource.remove(); // remove old event source
        }

        calendar.addEventSource({ // add new event source
            url: '/citas/ver?medico_id=' + medico_id,
            id: 'mySource'
        });

        calendar.refetchEvents(); // refresh events
    });

    let calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        
        initialView: 'timeGridWeek',
        locale: 'es',
        themeSystem: 'bootstrap',
        
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay listWeek',
        },

        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Dia',
            list: 'Lista',
        },

        events: {
            url: urlCitas,
            id: 'mySource',
        },

        dateClick:function(info) {
            formulario.reset();
            formulario.idx.value = '-1';
            formulario.fechaHoraIni.value = info.dateStr.slice(0, 16);
            formulario.fechaHoraFin.value = sum45Minutes(info.dateStr.slice(0, 16));
            $('#cargarCita').modal('show');
        },

        eventClick:function(info) {
            let event = info.event;

            Swal.fire({
                title: "<strong>"+event._def.title+"</strong>",
                html: event._def.extendedProps.description,
                icon: "info",
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: `Editar`,
                confirmButtonAriaLabel: "Thumbs up, great!",
                cancelButtonText: `Cerrar`,
            }).then((result) => {
                if (result.isConfirmed) {
                    mostrarCargando(true);
                    let id = event._def.publicId;
                    
                    fetch('/citas/editar/'+id)
                    .then(res => res.json())
                    .then(data => {
                        formulario.idx.value = data.id_cita;
                        formulario.doctor.value = data.medico_id;
                        formulario.fechaHoraIni.value = data.fec_inicio.slice(0, 16);
                        formulario.fechaHoraFin.value = data.fec_fin.slice(0, 16);
                        formulario.sala.value = data.sala_id;
                        formulario.paciente.value = data.paciente_id;
                        formulario.tipoConsulta.value = data.tipo_consulta_id;
                        formulario.entreCitas.value = data.entreCitas;
                        formulario.notas.value = data.observaciones;
                        mostrarCargando(false);
                    })
                    .catch(err => console.log(err));

                    $('#cargarCita').modal('show');
                }
            });
        }

    });

    document.getElementById('btnGuardar').addEventListener('click', function() {

        let url;

        let id = document.getElementById('idx').value;
        let doctor = document.getElementById('doctor').value;
        let fechaIni = document.getElementById('fechaHoraIni').value;
        let fechaFin = document.getElementById('fechaHoraFin').value;
        let sala = document.getElementById('sala').value;
        let paciente = document.getElementById('paciente').value;
        let tipo = document.getElementById('tipoConsulta').value;
        let entreCitas = document.getElementById('entreCitas').value;
        let notas = document.getElementById('notas').value;

        console.log(doctor);
        
        let datos = {
            medico_id: doctor,
            paciente_id: paciente,
            fec_inicio: fechaIni,
            fec_fin: fechaFin,
            estado: 'Pendiente',
            sala_id: sala,
            tipo_consulta_id: tipo,
            entreCitas: entreCitas,
            observaciones: notas,
        };

        console.log(datos);

        if(id == '-1') {
            url = '/citas';
        }else{
            url = '/citas/actualizar';
            datos.id_cita = id;
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(datos),
        })
        .then(res => res.json())
        .then(data => {
            if(data == 'Cita creada correctamente') {
                recargarCalendario();
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: data,
                    showConfirmButton: false,
                    timer: 1500
                });
            }else if(data == 'Cita actualizada correctamente') {
                recargarCalendario();

                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: data,
                    showConfirmButton: false,
                    timer: 1500
                });
            }else{
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: data,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
        .catch(err => console.log(err));
    });

    document.getElementById('btnCancelar').addEventListener('click', function() {
        formulario.reset();
    });

    calendar.render();
});

function sum45Minutes(fecha) {
    // Parseamos la fecha en formato "yyyy-MM-ddThh:mm"
    let fechaHoraIniValueDate = new Date(fecha);

    // Sumamos 45 minutos a la fecha
    fechaHoraIniValueDate.setMinutes(fechaHoraIniValueDate.getMinutes() + 45);

    // Obtenemos los componentes de la fecha y hora
    let año = fechaHoraIniValueDate.getFullYear();
    let mes = ("0" + (fechaHoraIniValueDate.getMonth() + 1)).slice(-2); // Sumamos 1 al mes porque los meses van de 0 a 11
    let dia = ("0" + fechaHoraIniValueDate.getDate()).slice(-2);
    let horas = ("0" + fechaHoraIniValueDate.getHours()).slice(-2);
    let minutos = ("0" + fechaHoraIniValueDate.getMinutes()).slice(-2);

    // Construimos la cadena de fecha y hora en el formato deseado
    let fechaHoraIniValueDatePlus45FormatString = `${año}-${mes}-${dia}T${horas}:${minutos}`;

    return fechaHoraIniValueDatePlus45FormatString;
}

function mostrarCargando(value) {
    if(value) {
        formulario.style.opacity = 0.3;
        document.getElementById('cargando').style.display = 'block';
    }else{
        formulario.style.opacity = 1;
        document.getElementById('cargando').style.display = 'none';
    }
}

function recargarCalendario() {
    $('#cargarCita').modal('hide');
    formulario.reset();
    formulario.idx.value = '-1';
    calendar.refetchEvents();
}