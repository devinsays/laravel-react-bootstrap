import React from 'react';
import { BrowserRouter as Router, Switch } from 'react-router-dom';
import routes from './routes';
import PublicRoute from './Public';
import PrivateRoute from './Private';
import SplitRoute from './Split';

const Routes = () => (
  <Router>
    <Switch>
      {routes.map((route) => {
        if (route.auth && route.fallback) {
          return <SplitRoute key={route.path} {...route} />;
        } if (route.auth) {
          return <PrivateRoute key={route.path} {...route} />;
        }
        return <PublicRoute key={route.path} {...route} />;
      })}
    </Switch>
  </Router>
);

export default Routes;
