const path = require('path');

module.exports = {
  webpack: {
    configure: (webpackConfig) => {
      // Fix for webpack-dev-server compatibility issues
      if (webpackConfig.devServer) {
        webpackConfig.devServer.setupMiddlewares = webpackConfig.devServer.onAfterSetupMiddleware;
        delete webpackConfig.devServer.onAfterSetupMiddleware;
        delete webpackConfig.devServer.onBeforeSetupMiddleware;
      }
      
      return webpackConfig;
    }
  },
  devServer: (devServerConfig) => {
    devServerConfig.setupMiddlewares = (middlewares, devServer) => {
      return middlewares;
    };
    return devServerConfig;
  },
  eslint: {
    enable: true,
    mode: 'extends',
    configure: {
      rules: {
        'no-unused-vars': 'warn',
        'react-hooks/exhaustive-deps': 'warn'
      }
    }
  }
};
