import './stimulus_bootstrap.js';
import './styles/app.css';

// Désactiver Turbo Drive
import * as Turbo from '@hotwired/turbo';
Turbo.session.drive = false;

console.log('test');