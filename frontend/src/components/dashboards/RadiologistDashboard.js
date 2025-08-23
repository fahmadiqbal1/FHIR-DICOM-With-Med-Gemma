import React, { useState, useEffect } from 'react';
import { 
  CameraIcon, 
  CheckCircleIcon, 
  ClockIcon,
  ExclamationTriangleIcon,
  EyeIcon,
  DocumentTextIcon,
  PhotoIcon,
  ArrowUpTrayIcon
} from '@heroicons/react/24/outline';
import { DashboardAPI } from '../../services/api';

const RadiologistDashboard = () => {
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const loadStats = async () => {
      try {
        setLoading(true);
        const data = await DashboardAPI.getRadiologistStats();
        setStats(data);
      } catch (err) {
        setError('Failed to load radiology statistics');
        console.error('Radiology stats error:', err);
        // Mock data for demo
        setStats({
          pending_studies: 8,
          in_progress: 2,
          completed_today: 15,
          total_revenue: 12500,
          urgent_studies: 3,
          equipment_online: 4,
          avg_report_time: '45 minutes',
          recent_studies: [
            { 
              id: 1, 
              patient: 'Mary Johnson', 
              study: 'Chest CT', 
              priority: 'urgent', 
              status: 'pending',
              ordered_time: '2 hours ago'
            },
            { 
              id: 2, 
              patient: 'Robert Smith', 
              study: 'Brain MRI', 
              priority: 'routine', 
              status: 'in_progress',
              ordered_time: '4 hours ago'
            },
            { 
              id: 3, 
              patient: 'Lisa Brown', 
              study: 'Abdominal X-Ray', 
              priority: 'routine', 
              status: 'completed',
              ordered_time: '6 hours ago'
            }
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
          <p className="mt-4 text-gray-600">Loading radiology dashboard...</p>
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
          <h1 className="text-3xl font-bold text-gray-900">Radiology Dashboard</h1>
          <p className="mt-2 text-gray-600">Imaging studies and diagnostic reporting</p>
        </div>

        {/* Stats Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <StatCard
            icon={ClockIcon}
            title="Pending Studies"
            value={stats.pending_studies}
            subtitle="Awaiting interpretation"
            color="yellow"
          />
          <StatCard
            icon={EyeIcon}
            title="In Progress"
            value={stats.in_progress}
            subtitle="Currently reviewing"
            color="blue"
          />
          <StatCard
            icon={CheckCircleIcon}
            title="Completed Today"
            value={stats.completed_today}
            subtitle="Reports finalized"
            color="green"
          />
          <StatCard
            icon={ExclamationTriangleIcon}
            title="Urgent Studies"
            value={stats.urgent_studies}
            subtitle="Require immediate attention"
            color="red"
          />
        </div>

        {/* Equipment and Performance */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          {/* Equipment Status */}
          <div className="bg-white rounded-lg shadow p-6">
            <h3 className="text-lg font-medium text-gray-900 mb-4">Imaging Equipment</h3>
            <div className="space-y-3">
              <div className="flex items-center justify-between">
                <div className="flex items-center">
                  <CameraIcon className="h-5 w-5 text-gray-400 mr-2" />
                  <span className="text-sm text-gray-600">CT Scanner A</span>
                </div>
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  Online
                </span>
              </div>
              <div className="flex items-center justify-between">
                <div className="flex items-center">
                  <CameraIcon className="h-5 w-5 text-gray-400 mr-2" />
                  <span className="text-sm text-gray-600">MRI Unit 1</span>
                </div>
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  Online
                </span>
              </div>
              <div className="flex items-center justify-between">
                <div className="flex items-center">
                  <CameraIcon className="h-5 w-5 text-gray-400 mr-2" />
                  <span className="text-sm text-gray-600">X-Ray Room 2</span>
                </div>
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                  Maintenance
                </span>
              </div>
              <div className="flex items-center justify-between">
                <div className="flex items-center">
                  <CameraIcon className="h-5 w-5 text-gray-400 mr-2" />
                  <span className="text-sm text-gray-600">Ultrasound Unit</span>
                </div>
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  Online
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
                  <span className="text-sm text-gray-600">Average Report Time</span>
                  <span className="text-sm font-medium">{stats.avg_report_time}</span>
                </div>
                <div className="mt-2 bg-gray-200 rounded-full h-2">
                  <div className="bg-blue-500 h-2 rounded-full" style={{ width: '70%' }}></div>
                </div>
              </div>
              <div>
                <div className="flex justify-between items-center">
                  <span className="text-sm text-gray-600">Report Accuracy Rate</span>
                  <span className="text-sm font-medium">99.2%</span>
                </div>
                <div className="mt-2 bg-gray-200 rounded-full h-2">
                  <div className="bg-green-500 h-2 rounded-full" style={{ width: '99.2%' }}></div>
                </div>
              </div>
              <div>
                <div className="flex justify-between items-center">
                  <span className="text-sm text-gray-600">Turnaround Efficiency</span>
                  <span className="text-sm font-medium">89%</span>
                </div>
                <div className="mt-2 bg-gray-200 rounded-full h-2">
                  <div className="bg-blue-500 h-2 rounded-full" style={{ width: '89%' }}></div>
                </div>
              </div>
              <div>
                <div className="flex justify-between items-center">
                  <span className="text-sm text-gray-600">Revenue Today</span>
                  <span className="text-sm font-medium">{DashboardAPI.formatCurrency(stats.total_revenue)}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Recent Studies */}
        <div className="bg-white rounded-lg shadow mb-8">
          <div className="px-6 py-4 border-b border-gray-200">
            <h3 className="text-lg font-medium text-gray-900">Recent Imaging Studies</h3>
          </div>
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Patient
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Study Type
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Priority
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Ordered
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {stats.recent_studies.map((study) => (
                  <tr key={study.id}>
                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {study.patient}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {study.study}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        study.priority === 'urgent' 
                          ? 'bg-red-100 text-red-800'
                          : 'bg-blue-100 text-blue-800'
                      }`}>
                        {study.priority}
                      </span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        study.status === 'completed'
                          ? 'bg-green-100 text-green-800'
                          : study.status === 'in_progress'
                          ? 'bg-yellow-100 text-yellow-800'
                          : 'bg-gray-100 text-gray-800'
                      }`}>
                        {study.status}
                      </span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {study.ordered_time}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div className="flex space-x-2">
                        {study.status === 'pending' && (
                          <button className="text-blue-600 hover:text-blue-900">
                            <EyeIcon className="h-4 w-4 inline mr-1" />
                            Review
                          </button>
                        )}
                        {study.status === 'in_progress' && (
                          <button className="text-green-600 hover:text-green-900">
                            <DocumentTextIcon className="h-4 w-4 inline mr-1" />
                            Finalize
                          </button>
                        )}
                        <button className="text-gray-600 hover:text-gray-900">
                          <PhotoIcon className="h-4 w-4 inline mr-1" />
                          Images
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
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          <button className="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors">
            <EyeIcon className="h-5 w-5 inline mr-2" />
            View Worklist
          </button>
          <button className="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors">
            <DocumentTextIcon className="h-5 w-5 inline mr-2" />
            Create Report
          </button>
          <button className="bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition-colors">
            <ArrowUpTrayIcon className="h-5 w-5 inline mr-2" />
            Upload Images
          </button>
          <button className="bg-gray-600 text-white px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors">
            <CameraIcon className="h-5 w-5 inline mr-2" />
            Equipment Status
          </button>
        </div>
      </div>
    </div>
  );
};

export default RadiologistDashboard;
