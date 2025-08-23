import React, { useState, useEffect } from 'react';
import { 
  BeakerIcon, 
  CheckCircleIcon, 
  ClockIcon,
  ExclamationTriangleIcon,
  BanknotesIcon,
  CalendarIcon
} from '@heroicons/react/24/outline';
import { DashboardAPI } from '../../services/api';

const LabTechDashboard = () => {
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const loadStats = async () => {
      try {
        setLoading(true);
        const data = await DashboardAPI.getLabStats();
        setStats(data);
      } catch (err) {
        setError('Failed to load lab statistics');
        console.error('Lab stats error:', err);
        // Mock data for demo
        setStats({
          pending_orders: 5,
          in_progress: 3,
          completed_today: 12,
          total_revenue: 4200,
          pending_samples: 2,
          equipment_status: 'online',
          turnaround_time: '2.5 hours',
          recent_orders: [
            { id: 1, patient: 'John Doe', test: 'Complete Blood Count', priority: 'routine', status: 'pending' },
            { id: 2, patient: 'Jane Smith', test: 'Lipid Panel', priority: 'urgent', status: 'in_progress' },
            { id: 3, patient: 'Bob Johnson', test: 'Thyroid Function', priority: 'routine', status: 'completed' }
          ]
        });
      } finally {
        setLoading(false);
      }
    };

    loadStats();
  }, []);

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
          <p className="mt-4 text-gray-600">Loading lab dashboard...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center text-red-600">
          <ExclamationTriangleIcon className="h-12 w-12 mx-auto mb-4" />
          <p>{error}</p>
        </div>
      </div>
    );
  }

  const StatCard = ({ icon: Icon, title, value, subtitle, color = "blue" }) => (
    <div className="bg-white rounded-lg shadow p-6">
      <div className="flex items-center">
        <div className={`flex-shrink-0 p-3 rounded-md bg-${color}-100`}>
          <Icon className={`h-6 w-6 text-${color}-600`} />
        </div>
        <div className="ml-4">
          <h3 className="text-sm font-medium text-gray-900">{title}</h3>
          <p className="text-2xl font-bold text-gray-900">{value}</p>
          {subtitle && <p className="text-sm text-gray-500">{subtitle}</p>}
        </div>
      </div>
    </div>
  );

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">Lab Tech Dashboard</h1>
          <p className="mt-2 text-gray-600">Laboratory operations and test management</p>
        </div>

        {/* Stats Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <StatCard
            icon={ClockIcon}
            title="Pending Orders"
            value={stats.pending_orders}
            subtitle="Awaiting sample collection"
            color="yellow"
          />
          <StatCard
            icon={BeakerIcon}
            title="In Progress"
            value={stats.in_progress}
            subtitle="Currently being processed"
            color="blue"
          />
          <StatCard
            icon={CheckCircleIcon}
            title="Completed Today"
            value={stats.completed_today}
            subtitle="Tests finished"
            color="green"
          />
          <StatCard
            icon={BanknotesIcon}
            title="Revenue Today"
            value={DashboardAPI.formatCurrency(stats.total_revenue)}
            subtitle="Laboratory earnings"
            color="indigo"
          />
        </div>

        {/* Lab Status */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          {/* Equipment Status */}
          <div className="bg-white rounded-lg shadow p-6">
            <h3 className="text-lg font-medium text-gray-900 mb-4">Equipment Status</h3>
            <div className="space-y-3">
              <div className="flex items-center justify-between">
                <span className="text-sm text-gray-600">Hematology Analyzer</span>
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  Online
                </span>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-sm text-gray-600">Chemistry Analyzer</span>
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  Online
                </span>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-sm text-gray-600">Microscope Station</span>
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                  Maintenance
                </span>
              </div>
            </div>
          </div>

          {/* Performance Metrics */}
          <div className="bg-white rounded-lg shadow p-6">
            <h3 className="text-lg font-medium text-gray-900 mb-4">Performance Metrics</h3>
            <div className="space-y-4">
              <div>
                <div className="flex justify-between items-center">
                  <span className="text-sm text-gray-600">Average Turnaround Time</span>
                  <span className="text-sm font-medium">{stats.turnaround_time}</span>
                </div>
                <div className="mt-2 bg-gray-200 rounded-full h-2">
                  <div className="bg-green-500 h-2 rounded-full" style={{ width: '75%' }}></div>
                </div>
              </div>
              <div>
                <div className="flex justify-between items-center">
                  <span className="text-sm text-gray-600">Quality Control Pass Rate</span>
                  <span className="text-sm font-medium">98.5%</span>
                </div>
                <div className="mt-2 bg-gray-200 rounded-full h-2">
                  <div className="bg-green-500 h-2 rounded-full" style={{ width: '98.5%' }}></div>
                </div>
              </div>
              <div>
                <div className="flex justify-between items-center">
                  <span className="text-sm text-gray-600">Sample Processing Efficiency</span>
                  <span className="text-sm font-medium">92%</span>
                </div>
                <div className="mt-2 bg-gray-200 rounded-full h-2">
                  <div className="bg-blue-500 h-2 rounded-full" style={{ width: '92%' }}></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Recent Orders */}
        <div className="bg-white rounded-lg shadow">
          <div className="px-6 py-4 border-b border-gray-200">
            <h3 className="text-lg font-medium text-gray-900">Recent Lab Orders</h3>
          </div>
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Patient
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Test
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Priority
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {stats.recent_orders.map((order) => (
                  <tr key={order.id}>
                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {order.patient}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {order.test}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        order.priority === 'urgent' 
                          ? 'bg-red-100 text-red-800'
                          : 'bg-blue-100 text-blue-800'
                      }`}>
                        {order.priority}
                      </span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        order.status === 'completed'
                          ? 'bg-green-100 text-green-800'
                          : order.status === 'in_progress'
                          ? 'bg-yellow-100 text-yellow-800'
                          : 'bg-gray-100 text-gray-800'
                      }`}>
                        {order.status}
                      </span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div className="flex space-x-2">
                        {order.status === 'pending' && (
                          <button className="text-blue-600 hover:text-blue-900">
                            Start Test
                          </button>
                        )}
                        {order.status === 'in_progress' && (
                          <button className="text-green-600 hover:text-green-900">
                            Complete
                          </button>
                        )}
                        <button className="text-gray-600 hover:text-gray-900">
                          View Details
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Quick Actions */}
        <div className="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
          <button className="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors">
            <BeakerIcon className="h-5 w-5 inline mr-2" />
            Process New Sample
          </button>
          <button className="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors">
            <CheckCircleIcon className="h-5 w-5 inline mr-2" />
            Quality Control Check
          </button>
          <button className="bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition-colors">
            <CalendarIcon className="h-5 w-5 inline mr-2" />
            Schedule Maintenance
          </button>
        </div>
      </div>
    </div>
  );
};

export default LabTechDashboard;
