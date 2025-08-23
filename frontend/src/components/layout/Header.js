import React from 'react';
import { 
  BellIcon, 
  MagnifyingGlassIcon,
  Cog6ToothIcon,
  ArrowRightOnRectangleIcon
} from '@heroicons/react/24/outline';

const Header = ({ user, onLogout }) => {
  const getRoleTitle = () => {
    switch (user.role) {
      case 'admin':
        return 'System Administrator';
      case 'doctor':
        return 'Medical Doctor';
      case 'owner':
        return 'Hospital Owner';
      case 'lab':
        return 'Laboratory Technician';
      case 'radiologist':
        return 'Radiologist';
      default:
        return 'Healthcare Professional';
    }
  };

  return (
    <div className="bg-white shadow-sm border-b border-gray-200">
      <div className="px-6 py-4">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-lg font-semibold text-gray-900">
              {getRoleTitle()} Portal
            </h1>
            <p className="text-sm text-gray-500">
              Welcome back, {user.name}
            </p>
          </div>
          
          <div className="flex items-center space-x-4">
            {/* Search */}
            <div className="relative hidden lg:block">
              <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <MagnifyingGlassIcon className="h-5 w-5 text-gray-400" />
              </div>
              <input
                type="text"
                placeholder="Search..."
                className="block w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
              />
            </div>

            {/* Notifications */}
            <button className="p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-md">
              <BellIcon className="h-6 w-6" />
            </button>

            {/* Settings */}
            <button className="p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-md">
              <Cog6ToothIcon className="h-6 w-6" />
            </button>

            {/* Profile Avatar */}
            <div className="flex items-center">
              <div className="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center">
                <span className="text-sm font-medium text-white">
                  {user.name.charAt(0).toUpperCase()}
                </span>
              </div>
            </div>

            {/* Logout Button */}
            {onLogout && (
              <button
                onClick={onLogout}
                className="flex items-center space-x-2 px-3 py-2 text-sm text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors"
                title="Switch User / Logout"
              >
                <ArrowRightOnRectangleIcon className="h-4 w-4" />
                <span className="hidden sm:block">Switch User</span>
              </button>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default Header;
