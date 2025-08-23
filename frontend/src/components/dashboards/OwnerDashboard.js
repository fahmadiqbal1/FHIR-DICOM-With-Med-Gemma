import React, { useState, useEffect } from 'react';
import { 
  CurrencyDollarIcon, 
  ChartBarIcon, 
  BuildingOfficeIcon, 
  BanknotesIcon,
  ArrowTrendingUpIcon,
  ArrowTrendingDownIcon 
} from '@heroicons/react/24/outline';
import { DashboardAPI } from '../../services/api';

const OwnerDashboard = () => {
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const loadStats = async () => {
      try {
        setLoading(true);
        const data = await DashboardAPI.getOwnerStats();
        setStats(data);
      } catch (err) {
        setError('Failed to load owner statistics');
        console.error('Owner stats error:', err);
        // Mock data for demo
        setStats({
          total_revenue: 125000,
          monthly_revenue: 45000,
          profit_today: 8500,
          monthly_profit: 25000,
          expenses: {
            salaries: 65000,
            equipment: 15000,
            utilities: 8000,
            supplies: 12000,
            other: 5000
          },
          revenue_by_role: {
            doctors: 75000,
            lab_techs: 25000,
            radiologists: 20000,
            admin_fees: 5000
          }
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
          <p className="mt-4 text-gray-600">Loading owner dashboard...</p>
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

  const statCards = [
    {
      name: 'Total Revenue',
      value: DashboardAPI.formatCurrency(stats.total_revenue || 0),
      icon: CurrencyDollarIcon,
      color: 'bg-blue-500',
      textColor: 'text-blue-600',
      bgColor: 'bg-blue-50'
    },
    {
      name: 'Monthly Revenue',
      value: DashboardAPI.formatCurrency(stats.monthly_revenue || 0),
      icon: ChartBarIcon,
      color: 'bg-green-500',
      textColor: 'text-green-600',
      bgColor: 'bg-green-50'
    },
    {
      name: 'Today\'s Profit',
      value: DashboardAPI.formatCurrency(stats.profit_today || 0),
      icon: stats.profit_today >= 0 ? ArrowTrendingUpIcon : ArrowTrendingDownIcon,
      color: stats.profit_today >= 0 ? 'bg-green-500' : 'bg-red-500',
      textColor: stats.profit_today >= 0 ? 'text-green-600' : 'text-red-600',
      bgColor: stats.profit_today >= 0 ? 'bg-green-50' : 'bg-red-50'
    },
    {
      name: 'Monthly Profit',
      value: DashboardAPI.formatCurrency(stats.monthly_profit || 0),
      icon: stats.monthly_profit >= 0 ? ArrowTrendingUpIcon : ArrowTrendingDownIcon,
      color: stats.monthly_profit >= 0 ? 'bg-green-500' : 'bg-red-500',
      textColor: stats.monthly_profit >= 0 ? 'text-green-600' : 'text-red-600',
      bgColor: stats.monthly_profit >= 0 ? 'bg-green-50' : 'bg-red-50'
    }
  ];

  const revenueByRole = stats.revenue_by_role || {};
  const roleStats = [
    { role: 'Doctors', amount: revenueByRole.doctors || 0, color: 'bg-purple-500' },
    { role: 'Lab Techs', amount: revenueByRole.lab_techs || 0, color: 'bg-yellow-500' },
    { role: 'Radiologists', amount: revenueByRole.radiologists || 0, color: 'bg-red-500' },
    { role: 'Admin Fees', amount: revenueByRole.admin_fees || 0, color: 'bg-gray-500' }
  ];

  const expenses = stats.expenses || {};
  const expenseBreakdown = [
    { category: 'Salaries', amount: expenses.salaries || 0, color: 'bg-blue-500' },
    { category: 'Equipment', amount: expenses.equipment || 0, color: 'bg-green-500' },
    { category: 'Utilities', amount: expenses.utilities || 0, color: 'bg-yellow-500' },
    { category: 'Supplies', amount: expenses.supplies || 0, color: 'bg-purple-500' },
    { category: 'Other', amount: expenses.other || 0, color: 'bg-gray-500' }
  ];

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">Owner Dashboard</h1>
          <p className="mt-2 text-gray-600">Financial overview and business analytics</p>
        </div>

        {/* Stats Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          {statCards.map((item) => (
            <div key={item.name} className="bg-white rounded-lg shadow p-6">
              <div className="flex items-center">
                <div className={`flex-shrink-0 p-3 rounded-md ${item.bgColor}`}>
                  <item.icon className={`h-6 w-6 ${item.textColor}`} />
                </div>
                <div className="ml-4">
                  <h3 className="text-sm font-medium text-gray-900">{item.name}</h3>
                  <p className="text-2xl font-bold text-gray-900">{item.value}</p>
                </div>
              </div>
            </div>
          ))}
        </div>

        {/* Financial Breakdown */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          {/* Revenue by Role */}
          <div className="bg-white rounded-lg shadow p-6">
            <h3 className="text-lg font-medium text-gray-900 mb-4">Revenue by Role</h3>
            <div className="space-y-4">
              {roleStats.map((item) => {
                const percentage = stats.total_revenue > 0 ? (item.amount / stats.total_revenue * 100).toFixed(1) : 0;
                return (
                  <div key={item.role}>
                    <div className="flex justify-between items-center mb-2">
                      <span className="text-sm font-medium text-gray-700">{item.role}</span>
                      <span className="text-sm text-gray-900">
                        {DashboardAPI.formatCurrency(item.amount)} ({percentage}%)
                      </span>
                    </div>
                    <div className="w-full bg-gray-200 rounded-full h-2">
                      <div 
                        className={`${item.color} h-2 rounded-full`} 
                        style={{ width: `${percentage}%` }}
                      ></div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>

          {/* Expense Breakdown */}
          <div className="bg-white rounded-lg shadow p-6">
            <h3 className="text-lg font-medium text-gray-900 mb-4">Monthly Expenses</h3>
            <div className="space-y-4">
              {expenseBreakdown.map((item) => {
                const totalExpenses = Object.values(expenses).reduce((sum, val) => sum + val, 0);
                const percentage = totalExpenses > 0 ? (item.amount / totalExpenses * 100).toFixed(1) : 0;
                return (
                  <div key={item.category}>
                    <div className="flex justify-between items-center mb-2">
                      <span className="text-sm font-medium text-gray-700">{item.category}</span>
                      <span className="text-sm text-gray-900">
                        {DashboardAPI.formatCurrency(item.amount)} ({percentage}%)
                      </span>
                    </div>
                    <div className="w-full bg-gray-200 rounded-full h-2">
                      <div 
                        className={`${item.color} h-2 rounded-full`} 
                        style={{ width: `${percentage}%` }}
                      ></div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </div>

        {/* Profit Analysis */}
        <div className="bg-white rounded-lg shadow p-6 mb-8">
          <h3 className="text-lg font-medium text-gray-900 mb-4">Profit Analysis</h3>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div className="text-center">
              <p className="text-sm text-gray-500">Total Revenue</p>
              <p className="text-2xl font-bold text-blue-600">
                {DashboardAPI.formatCurrency(stats.total_revenue)}
              </p>
            </div>
            <div className="text-center">
              <p className="text-sm text-gray-500">Total Expenses</p>
              <p className="text-2xl font-bold text-red-600">
                {DashboardAPI.formatCurrency(Object.values(expenses).reduce((sum, val) => sum + val, 0))}
              </p>
            </div>
            <div className="text-center">
              <p className="text-sm text-gray-500">Net Profit</p>
              <p className={`text-2xl font-bold ${
                stats.monthly_profit >= 0 ? 'text-green-600' : 'text-red-600'
              }`}>
                {DashboardAPI.formatCurrency(stats.monthly_profit)}
              </p>
            </div>
          </div>
        </div>

        {/* Quick Actions */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <button className="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors">
            <BuildingOfficeIcon className="h-5 w-5 inline mr-2" />
            Generate Financial Report
          </button>
          <button className="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors">
            <BanknotesIcon className="h-5 w-5 inline mr-2" />
            Review Revenue Splits
          </button>
          <button className="bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition-colors">
            <ChartBarIcon className="h-5 w-5 inline mr-2" />
            View Detailed Analytics
          </button>
        </div>
      </div>
    </div>
  );
};

export default OwnerDashboard;
