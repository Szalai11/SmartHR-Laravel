@extends('layouts.app')

@section('title', 'Calendrier des absences')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec statistiques -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-secondary">
                <i class="fas fa-calendar-alt text-primary mr-2"></i>
                Calendrier des absences
            </h1>
            <p class="text-gray-500 text-sm mt-1">Visualisation des congés et permissions validés</p>
        </div>
        <div class="flex gap-3">
            <div class="flex items-center gap-2 px-3 py-1.5 bg-green-50 rounded-lg border border-green-200">
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                <span class="text-sm font-medium text-green-700">Congé</span>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 bg-orange-50 rounded-lg border border-orange-200">
                <span class="w-3 h-3 bg-orange-500 rounded-full"></span>
                <span class="text-sm font-medium text-orange-700">Permission</span>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-200">
                <span class="w-3 h-3 bg-primary rounded-full"></span>
                <span class="text-sm font-medium text-primary">Aujourd'hui</span>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total absences</p>
                    <p class="text-2xl font-bold text-secondary">{{ $conges->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-week text-primary"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Congés</p>
                    <p class="text-2xl font-bold text-success">{{ $conges->where('backgroundColor', '#10B981')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-umbrella-beach text-success"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Permissions</p>
                    <p class="text-2xl font-bold text-warning">{{ $conges->where('backgroundColor', '#F59E0B')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-warning"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Collaborateurs concernés</p>
                    <p class="text-2xl font-bold text-primary">{{ $conges->pluck('title')->unique()->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-6">
            <div id="calendar"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        
        if (!calendarEl) return;
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            // Configuration générale
            locale: 'fr',
            initialView: 'dayGridMonth',
            timeZone: 'local',
            
            // Header personnalisé
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            
            // Textes personnalisés
            buttonText: {
                today: 'Aujourd\'hui',
                month: 'Mois',
                week: 'Semaine',
                list: 'Liste',
                prev: '‹',
                next: '›'
            },
            
            // Style et apparence
            height: 'auto',
            contentHeight: 'auto',
            firstDay: 1, // Lundi comme premier jour
            displayEventTime: false,
            eventDisplay: 'block',
            
            // Événements
            events: @json($conges),
            
            // Personnalisation des événements
            eventDidMount: function(info) {
                // Ajouter un tooltip
                info.el.setAttribute('title', info.event.title);
            },
            
            // Style des cellules
            dayCellClassNames: function(date) {
                if (date.date.toDateString() === new Date().toDateString()) {
                    return ['bg-primary/5'];
                }
                return [];
            },
            
            // Clic sur événement
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                alert('📅 ' + info.event.title);
            },
        });
        
        calendar.render();
        
        // Ajouter des styles personnalisés après le rendu
        setTimeout(function() {
            var todayCells = document.querySelectorAll('.fc-day-today');
            todayCells.forEach(function(cell) {
                cell.style.backgroundColor = '#EFF6FF';
            });
        }, 100);
    });
</script>
@endpush

@push('styles')
<style>
    /* Styles personnalisés du calendrier */
    .fc {
        font-family: 'Poppins', sans-serif;
    }
    
    .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1F2937;
    }
    
    .fc-button {
        background-color: white;
        border: 1px solid #E5E7EB;
        color: #4B5563;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        text-transform: capitalize;
    }
    
    .fc-button:hover {
        background-color: #F3F4F6;
        border-color: #2563EB;
        color: #2563EB;
    }
    
    .fc-button-primary {
        background-color: #2563EB;
        border-color: #2563EB;
        color: white;
    }
    
    .fc-button-primary:hover {
        background-color: #1D4ED8;
        border-color: #1D4ED8;
    }
    
    .fc-button-primary:not(:disabled):active,
    .fc-button-primary:focus {
        background-color: #1D4ED8;
        border-color: #1D4ED8;
        box-shadow: none;
    }
    
    .fc-day-header {
        padding: 0.75rem;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        font-size: 0.75rem;
        background-color: #F9FAFB;
    }
    
    .fc-daygrid-day {
        transition: background-color 0.2s ease;
    }
    
    .fc-daygrid-day:hover {
        background-color: #F9FAFB;
    }
    
    .fc-daygrid-day-number {
        font-size: 0.875rem;
        color: #4B5563;
        padding: 0.5rem;
        font-weight: 500;
    }
    
    .fc-day-today .fc-daygrid-day-number {
        background-color: #2563EB;
        color: white;
        border-radius: 9999px;
        width: 2rem;
        height: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    
    .fc-event {
        border: none;
        border-radius: 0.5rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: transform 0.1s ease;
        margin: 1px 2px;
    }
    
    .fc-event:hover {
        transform: scale(1.02);
        filter: brightness(1.05);
    }
    
    .fc-list-table {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .fc-list-day-cushion {
        background-color: #F3F4F6;
        padding: 0.75rem;
        font-weight: 600;
        color: #1F2937;
    }
    
    .fc-list-event-title {
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .fc-toolbar {
            flex-direction: column;
            gap: 1rem;
        }
        
        .fc-toolbar-title {
            font-size: 1rem;
        }
        
        .fc-button {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        .fc-event {
            font-size: 0.65rem;
            padding: 0.125rem 0.25rem;
        }
    }
</style>
@endpush
@endsection