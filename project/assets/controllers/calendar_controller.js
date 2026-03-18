import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        url: String,
        date: String,
    }

    connect() {
        const { Calendar } = FullCalendar;

        this.calendar = new Calendar(this.element, {
            initialView: 'timeGridDay',
            initialDate: this.dateValue,
            locale: 'fr',
            height: 'auto',       // ← s'adapte au contenu
            slotMinTime: '08:00', // ← commence à 8h
            slotMaxTime: '17:00', // ← finit à 17h
            expandRows: true,     // ← étire les lignes pour remplir l'espace
            headerToolbar: {
                left: '',         // ← supprime les flèches
                center: 'title',
                right: ''
            },
            events: (info, successCallback, failureCallback) => {
                const url = new URL(this.urlValue, window.location.origin);
                url.searchParams.set('start', info.startStr);
                url.searchParams.set('end', info.endStr);

                fetch(url)
                    .then(r => r.json())
                    .then(successCallback)
                    .catch(failureCallback);
            },
            eventClick: ({ event }) => {
                const { room, speaker } = event.extendedProps;
                alert(`📍 Salle : ${room}\n🎤 Intervenant : ${speaker}`);
            },
        });

        this.calendar.render();
    }

    disconnect() {
        this.calendar.destroy();
    }
}