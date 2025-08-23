import React, { useState, useEffect } from 'react';
import { DashboardAPI } from '../../services/api';
import { 
  ChartBarIcon, 
  CurrencyDollarIcon, 
  UsersIcon, 
  ClipboardDocumentListIcon,
  ExclamationTriangleIcon 
} from '@heroicons/react/24/outline';

const AdminDashboard = () => {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        const result = await DashboardAPI.getAdminStats();
        setData(result);
        setError(null);
      } catch (err) {
        setError(err.message || 'Failed to load admin dashboard data');
        console.error('Admin dashboard error:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (loading) {
    return (
      <div className="animate-pulse">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          {[1, 2, 3, 4].map(i => (
            <div key={i} className="bg-gray-200 h-32 rounded-lg"></div>
          ))}
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="bg-red-50 border border-red-200 rounded-lg p-6">
        <div className="flex items-center">
          <ExclamationTriangleIcon className="h-6 w-6 text-red-600 mr-3" />
          <div>
            <h3 className="text-red-800 font-medium">Error Loading Dashboard</h3>
            <p className="text-red-600 text-sm mt-1">{error}</p>
          </div>
        </div>
      </div>
    );
  }

  const stats = [
    {
      name: 'Total Income Today',
      value: DashboardAPI.formatCurrency(data?.total_income || 0),
      icon: CurrencyDollarIcon,
      color: 'bg-green-500',
      textColor: 'text-green-600',
      bgColor: 'bg-green-50'
    },
    {
      name: 'Total Invoices',
      value: data?.total_invoices || 0,
      icon: ClipboardDocumentListIcon,
      color: 'bg-blue-500',
      textColor: 'text-blue-600',
      bgColor: 'bg-blue-50'
    },
    {
      name: 'New Patients',
      value: data?.new_patients || 0,
      icon: UsersIcon,
      color: 'bg-purple-500',
      textColor: 'text-purple-600',
      bgColor: 'bg-purple-50'
    },
    {
      name: 'Pending Requests',
      value: data?.pending_requests || 0,
      icon: ExclamationTriangleIcon,
      color: 'bg-yellow-500',
      textColor: 'text-yellow-600',
      bgColor: 'bg-yellow-50'
    }
  ];

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="bg-white shadow-soft rounded-lg p-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <p className="text-gray-600 mt-1">Today's overview - {data?.date}</p>
          </div>
          <div className="text-right">
            <div className="text-sm text-gray-500">System Status</div>
            <div className="flex items-center mt-1">
              <div className="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
              <span className="text-sm font-medium text-green-600">All Systems Operational</span>
            </div>
          </div>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat) => (
          <div key={stat.name} className="stat-card">
            <div className="flex items-center">
              <div className={`${stat.bgColor} p-3 rounded-lg`}>
                <stat.icon className={`h-6 w-6 ${stat.textColor}`} />
              </div>
              <div className="ml-4 flex-1">
                <div className="text-sm font-medium text-gray-500">{stat.name}</div>
                <div className="text-2xl font-bold text-gray-900">{stat.value}</div>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Revenue Split Analysis */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="card">
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-semibold text-gray-900">Revenue Distribution</h3>
            <ChartBarIcon className="h-5 w-5 text-gray-400" />
          </div>
          <div className="space-y-4">
            <div className="flex items-center justify-between">
              <div className="flex items-center">
                <div className="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                <span className="text-sm font-medium text-gray-700">Owner Share</span>
              </div>
              <span className="text-sm font-bold text-gray-900">
                {DashboardAPI.formatCurrency(data.owner_share || 0)}
              </span>
            </div>
            <div className="flex items-center justify-between">
              <div className="flex items-center">
                <div className="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                <span className="text-sm font-medium text-gray-700">Doctors Share</span>
              </div>
              <span className="text-sm font-bold text-gray-900">
                {DashboardAPI.formatCurrency(data.doctors_share || 0)}
              </span>
            </div>
            <div className="border-t pt-3 mt-3">
              <div className="flex items-center justify-between">
                <span className="text-sm font-semibold text-gray-700">Total Revenue</span>
                <span className="text-sm font-bold text-gray-900">
                  {DashboardAPI.formatCurrency(data.total_income || 0)}
                </span>
              </div>
            </div>
          </div>
        </div>

        {/* Top Doctors */}
        <div className="card">
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-semibold text-gray-900">Top Performing Doctors</h3>
            <UsersIcon className="h-5 w-5 text-gray-400" />
          </div>
          <div className="space-y-3">
            {data.top_doctors && data.top_doctors.length > 0 ? (
              data.top_doctors.slice(0, 5).map((doctor, index) => (
                <div key={doctor.id} className="flex items-center justify-between py-2">
                  <div className="flex items-center">
                    <div className={`w-6 h-6 rounded-full flex items-center justify-center text-xs font-medium text-white ${
                      index === 0 ? 'bg-yellow-500' : index === 1 ? 'bg-gray-400' : index === 2 ? 'bg-yellow-600' : 'bg-gray-300'
                    }`}>
                      {index + 1}
                    </div>
                    <div className="ml-3">
                      <div className="text-sm font-medium text-gray-900">Dr. {doctor.name}</div>
                      <div className="text-xs text-gray-500">{doctor.patients_seen || 0} patients</div>
                    </div>
                  </div>
                  <div className="text-right">
                    <div className="text-sm font-medium text-gray-900">
                      {DashboardAPI.formatCurrency(doctor.revenue || 0)}
                    </div>
                  </div>
                </div>
              ))
            ) : (
              <div className="text-center py-4 text-gray-500">
                <UsersIcon className="h-8 w-8 mx-auto mb-2 opacity-50" />
                <p className="text-sm">No doctor data available</p>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminDashboard;
