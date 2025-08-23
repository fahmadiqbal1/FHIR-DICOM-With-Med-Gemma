import React, { useState, useEffect } from 'react';
import { DashboardAPI } from '../../services/api';
import { 
  UserIcon, 
  CurrencyDollarIcon, 
  ClipboardDocumentListIcon,
  ExclamationTriangleIcon,
  BeakerIcon,
  CameraIcon 
} from '@heroicons/react/24/outline';

const DoctorDashboard = () => {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const loadData = async () => {
      try {
        setLoading(true);
        const doctorData = await DashboardAPI.getDoctorStats();
        setData(doctorData);
      } catch (err) {
        setError('Failed to load doctor statistics');
        console.error('Doctor stats error:', err);
        // Mock data for demo
        setData({
          doctor_name: 'Dr. Smith',
          date: new Date().toLocaleDateString(),
          patients_seen_today: 12,
          income_today: 2400,
          pending_results: 3,
          recent_requests: [
            { type: 'Lab', count: 8, pending: 2 },
            { type: 'Imaging', count: 4, pending: 1 }
          ]
        });
      } finally {
        setLoading(false);
      }
    };

    loadData();
  }, []);

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
          <p className="mt-4 text-gray-600">Loading doctor dashboard...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center text-red-600">
          <p>{error}</p>
        </div>
      </div>
    );
  }

  const stats = [
    {
      name: 'Patients Seen Today',
      value: data.patients_seen_today || 0,
      icon: UserIcon,
      color: 'bg-blue-500',
      textColor: 'text-blue-600',
      bgColor: 'bg-blue-50'
    },
    {
      name: 'Today\'s Earnings',
      value: DashboardAPI.formatCurrency(data.income_today || 0),
      icon: CurrencyDollarIcon,
      color: 'bg-green-500',
      textColor: 'text-green-600',
      bgColor: 'bg-green-50'
    },
    {
      name: 'Pending Results',
      value: data.pending_results || 0,
      icon: ExclamationTriangleIcon,
      color: 'bg-yellow-500',
      textColor: 'text-yellow-600',
      bgColor: 'bg-yellow-50'
    }
  ];

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div className="space-y-6">
      {/* Header */}
      <div className="bg-white shadow-soft rounded-lg p-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Welcome, {data.doctor_name}</h1>
            <p className="text-gray-600 mt-1">Your dashboard for {data.date}</p>
          </div>
          <div className="text-right">
            <div className="text-sm text-gray-500">Status</div>
            <div className="flex items-center mt-1">
              <div className="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
              <span className="text-sm font-medium text-green-600">Active</span>
            </div>
          </div>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
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

      {/* Recent Requests */}
      <div className="card">
        <div className="flex items-center justify-between mb-4">
          <h3 className="text-lg font-semibold text-gray-900">Today's Requests</h3>
          <ClipboardDocumentListIcon className="h-5 w-5 text-gray-400" />
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {data.recent_requests && data.recent_requests.map((request, index) => (
            <div key={index} className="border border-gray-200 rounded-lg p-4">
              <div className="flex items-center justify-between mb-3">
                <div className="flex items-center">
                  {request.type === 'Lab' ? (
                    <BeakerIcon className="h-5 w-5 text-blue-600 mr-2" />
                  ) : (
                    <CameraIcon className="h-5 w-5 text-purple-600 mr-2" />
                  )}
                  <span className="font-medium text-gray-900">{request.type} Orders</span>
                </div>
                <span className="text-2xl font-bold text-gray-900">{request.count || 0}</span>
              </div>
              <div className="flex items-center justify-between text-sm">
                <span className="text-gray-500">Pending:</span>
                <span className={`font-medium ${
                  request.pending > 0 ? 'text-yellow-600' : 'text-green-600'
                }`}>
                  {request.pending || 0}
                </span>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Quick Actions */}
      <div className="card">
        <h3 className="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <button className="btn-primary">
            <UserIcon className="h-4 w-4 mr-2" />
            New Patient
          </button>
          <button className="btn-secondary">
            <BeakerIcon className="h-4 w-4 mr-2" />
            Order Lab Test
          </button>
          <button className="btn-secondary">
            <CameraIcon className="h-4 w-4 mr-2" />
            Request Imaging
          </button>
        </div>
      </div>

      {/* Pending Results Alert */}
      {data.pending_results > 0 && (
        <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
          <div className="flex items-center">
            <ExclamationTriangleIcon className="h-5 w-5 text-yellow-600 mr-3" />
            <div>
              <h4 className="text-yellow-800 font-medium">
                {data.pending_results} Results Awaiting Review
              </h4>
              <p className="text-yellow-700 text-sm mt-1">
                You have completed lab results that need to be reviewed.
              </p>
            </div>
            <button className="ml-auto btn-warning">
              Review Results
            </button>
          </div>
        </div>
      )}
        </div>
      </div>
    </div>
  );
};

export default DoctorDashboard;