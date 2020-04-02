import React from 'react';
import PropTypes from 'prop-types';
import { Route } from 'react-router';
import { connect } from 'react-redux';
import Base from '../Base';

const SplitRoute = ({
  component: Component,
  fallback: Fallback,
  isAuthenticated,
  ...rest
}) => (
  <Route
    {...rest}
    render={(props) => (isAuthenticated ? (
      <Base>
        <Component {...props} />
      </Base>
    ) : (
      <Base>
        <Fallback {...props} />
      </Base>
    ))}
  />
);

SplitRoute.propTypes = {
  isAuthenticated: PropTypes.bool.isRequired,
};

const mapStateToProps = (state) => ({
  isAuthenticated: state.Auth.isAuthenticated,
});

export default connect(mapStateToProps)(SplitRoute);
