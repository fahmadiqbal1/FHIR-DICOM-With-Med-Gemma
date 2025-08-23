import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import DashboardApp from './components/DashboardApp';

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode>
    <DashboardApp />
  </React.StrictMode>
);
