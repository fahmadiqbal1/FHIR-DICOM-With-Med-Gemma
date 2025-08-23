import React, { useState } from 'react';
import { UserIcon, KeyIcon } from '@heroicons/react/24/outline';

const LoginPage = ({ onLogin }) => {
  const [selectedRole, setSelectedRole] = useState('admin');
  const [isLoading, setIsLoading] = useState(false);

  const roles = [
    { value: 'admin', label: 'Hospital Administrator', color: 'bg-blue-600' },
    { value: 'owner', label: 'Hospital Owner', color: 'bg-green-600' },
    { value: 'doctor', label: 'Doctor', color: 'bg-purple-600' },
    { value: 'lab', label: 'Lab Technician', color: 'bg-yellow-600' },
    { value: 'radiologist', label: 'Radiologist', color: 'bg-red-600' }
  ];

  const handleLogin = async (e) => {
    e.preventDefault();
    setIsLoading(true);
    
    // Simulate login delay
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    const user = {
      name: `${roles.find(r => r.value === selectedRole)?.label} Demo`,
      role: selectedRole,
      email: `${selectedRole}@hospital.com`
    };
    
    onLogin(user);
    setIsLoading(false);
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-600 to-purple-700 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8">
        <div className="text-center">
          <div className="mx-auto h-16 w-16 bg-white rounded-full flex items-center justify-center">
            <UserIcon className="h-8 w-8 text-blue-600" />
          </div>
          <h2 className="mt-6 text-3xl font-extrabold text-white">
            Healthcare Dashboard
          </h2>
          <p className="mt-2 text-sm text-blue-100">
            Select your role to access the dashboard
          </p>
        </div>
        
        <div className="bg-white rounded-lg shadow-xl p-8">
          <form onSubmit={handleLogin} className="space-y-6">
            <div>
              <label htmlFor="role" className="block text-sm font-medium text-gray-700 mb-2">
                Select Role
              </label>
              <select
                id="role"
                value={selectedRole}
                onChange={(e) => setSelectedRole(e.target.value)}
                className="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
              >
                {roles.map((role) => (
                  <option key={role.value} value={role.value}>
                    {role.label}
                  </option>
                ))}
              </select>
            </div>
            
            <div>
              <label htmlFor="credentials" className="block text-sm font-medium text-gray-700 mb-2">
                Demo Credentials
              </label>
              <div className="bg-gray-50 rounded-md p-4 text-sm text-gray-600">
                <p><strong>Username:</strong> {selectedRole}@hospital.com</p>
                <p><strong>Password:</strong> demo123</p>
                <p className="text-xs text-gray-500 mt-2">
                  * This is a demo system with mock authentication
                </p>
              </div>
            </div>

            <button
              type="submit"
              disabled={isLoading}
              className={`group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white ${
                isLoading 
                  ? 'bg-gray-400 cursor-not-allowed' 
                  : `${roles.find(r => r.value === selectedRole)?.color} hover:opacity-90`
              } focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors`}
            >
              {isLoading ? (
                <div className="flex items-center">
                  <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                  Logging in...
                </div>
              ) : (
                <div className="flex items-center">
                  <KeyIcon className="h-4 w-4 mr-2" />
                  Login as {roles.find(r => r.value === selectedRole)?.label}
                </div>
              )}
            </button>
          </form>

          <div className="mt-6 border-t border-gray-200 pt-4">
            <h4 className="text-sm font-medium text-gray-700 mb-2">Available Dashboards:</h4>
            <div className="space-y-1 text-xs text-gray-600">
              <p>• <strong>Admin:</strong> Revenue analytics, system overview, top doctors</p>
              <p>• <strong>Owner:</strong> Financial breakdowns, profit analysis, expenses</p>
              <p>• <strong>Doctor:</strong> Patient metrics, earnings, pending results</p>
              <p>• <strong>Lab Tech:</strong> Test orders, equipment status, sample processing</p>
              <p>• <strong>Radiologist:</strong> Imaging studies, reports, equipment monitoring</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;
