import React from 'react';
import { 
  HomeIcon,
  UserIcon,
  ChartBarIcon,
  Cog6ToothIcon,
  BeakerIcon,
  CameraIcon,
  BuildingOfficeIcon,
  CurrencyDollarIcon,
  ShieldCheckIcon
} from '@heroicons/react/24/outline';

const Sidebar = ({ user }) => {
  const getRoleNavigation = () => {
    const commonItems = [
      { name: 'Dashboard', icon: HomeIcon, href: '/', current: true },
      { name: 'Profile', icon: UserIcon, href: '/profile' },
      { name: 'Settings', icon: Cog6ToothIcon, href: '/settings' },
    ];

    switch (user.role) {
      case 'admin':
        return [
          { name: 'Dashboard', icon: ChartBarIcon, href: '/', current: true },
          { name: 'Users', icon: UserIcon, href: '/users' },
          { name: 'System Health', icon: ShieldCheckIcon, href: '/health' },
          { name: 'Reports', icon: ChartBarIcon, href: '/reports' },
          { name: 'Settings', icon: Cog6ToothIcon, href: '/settings' },
        ];

      case 'doctor':
        return [
          { name: 'Dashboard', icon: HomeIcon, href: '/', current: true },
          { name: 'Patients', icon: UserIcon, href: '/patients' },
          { name: 'Lab Orders', icon: BeakerIcon, href: '/lab-orders' },
          { name: 'Imaging', icon: CameraIcon, href: '/imaging' },
          { name: 'Profile', icon: UserIcon, href: '/profile' },
        ];

      case 'owner':
        return [
          { name: 'Dashboard', icon: ChartBarIcon, href: '/', current: true },
          { name: 'Revenue', icon: CurrencyDollarIcon, href: '/revenue' },
          { name: 'Analytics', icon: ChartBarIcon, href: '/analytics' },
          { name: 'Staff', icon: UserIcon, href: '/staff' },
          { name: 'Settings', icon: Cog6ToothIcon, href: '/settings' },
        ];

      case 'lab_tech':
        return [
          { name: 'Dashboard', icon: BeakerIcon, href: '/', current: true },
          { name: 'Lab Queue', icon: BeakerIcon, href: '/lab-queue' },
          { name: 'Results', icon: ChartBarIcon, href: '/results' },
          { name: 'Equipment', icon: Cog6ToothIcon, href: '/equipment' },
        ];

      case 'radiologist':
        return [
          { name: 'Dashboard', icon: CameraIcon, href: '/', current: true },
          { name: 'Studies', icon: CameraIcon, href: '/studies' },
          { name: 'Reports', icon: ChartBarIcon, href: '/reports' },
          { name: 'DICOM Viewer', icon: CameraIcon, href: '/viewer' },
        ];

      default:
        return commonItems;
    }
  };

  const navigation = getRoleNavigation();

  return (
    <div className="hidden md:flex md:w-64 md:flex-col">
      <div className="flex flex-col flex-grow pt-5 bg-white border-r border-gray-200 overflow-y-auto">
        <div className="flex items-center flex-shrink-0 px-4">
          <BuildingOfficeIcon className="h-8 w-8 text-primary-600" />
          <span className="ml-2 text-xl font-bold text-gray-900">HealthHub</span>
        </div>
        
        <div className="mt-5 flex-grow flex flex-col">
          <nav className="flex-1 px-2 space-y-1">
            {navigation.map((item) => (
              <a
                key={item.name}
                href={item.href}
                className={
                  item.current
                    ? 'sidebar-link-active'
                    : 'sidebar-link'
                }
              >
                <item.icon className="mr-3 h-5 w-5" />
                {item.name}
              </a>
            ))}
          </nav>
        </div>
        
        <div className="flex-shrink-0 p-4 border-t border-gray-200">
          <div className="flex items-center">
            <div className="flex-shrink-0">
              <div className="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center">
                <span className="text-sm font-medium text-white">
                  {user.name.charAt(0).toUpperCase()}
                </span>
              </div>
            </div>
            <div className="ml-3">
              <p className="text-sm font-medium text-gray-700">{user.name}</p>
              <p className="text-xs font-medium text-gray-500 capitalize">{user.role}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Sidebar;
