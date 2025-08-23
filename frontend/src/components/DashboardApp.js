import React, { useState, useEffect } from 'react';
import AdminDashboard from './dashboards/AdminDashboard';
import OwnerDashboard from './dashboards/OwnerDashboard';
import DoctorDashboard from './dashboards/DoctorDashboard';
import LabTechDashboard from './dashboards/LabTechDashboard';
import RadiologistDashboard from './dashboards/RadiologistDashboard';
import Sidebar from '../components/layout/Sidebar';
import Header from '../components/layout/Header';
import LoginPage from './LoginPage';

const DashboardApp = () => {
  const [user, setUser] = useState(null);

  // Check for stored user on component mount
  useEffect(() => {
    const storedUser = localStorage.getItem('demo_user');
    if (storedUser) {
      setUser(JSON.parse(storedUser));
    }
  }, []);

  const handleLogin = (userData) => {
    setUser(userData);
    localStorage.setItem('demo_user', JSON.stringify(userData));
  };

  const handleLogout = () => {
    setUser(null);
    localStorage.removeItem('demo_user');
  };

  const renderDashboard = () => {
    switch (user.role) {
      case 'admin':
        return <AdminDashboard />;
      case 'owner':
        return <OwnerDashboard />;
      case 'doctor':
        return <DoctorDashboard />;
      case 'lab':
        return <LabTechDashboard />;
      case 'radiologist':
        return <RadiologistDashboard />;
      default:
        return (
          <div className="min-h-screen bg-gray-50 flex items-center justify-center">
            <div className="text-center">
              <h2 className="text-2xl font-bold text-gray-900 mb-4">
                Dashboard Not Available
              </h2>
              <p className="text-gray-600">
                No dashboard configured for role: {user.role}
              </p>
            </div>
          </div>
        );
    }
  };

  if (!user) {
    return <LoginPage onLogin={handleLogin} />;
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="flex h-screen">
        <Sidebar user={user} />
        <div className="flex-1 flex flex-col overflow-hidden">
          <Header user={user} onLogout={handleLogout} />
          <main className="flex-1 overflow-y-auto">
            <div className="p-6">
              {renderDashboard()}
            </div>
          </main>
        </div>
      </div>
    </div>
  );
};

export default DashboardApp;
