import React from 'react';
import { connect } from 'react-redux';
import Header from './components/Header';

const Base = ({ children }) => (
  <div>
    <Header />
    <main>
      {children}
    </main>
  </div>
);

const mapStateToProps = state => ({
  isAuthenticated: state.Auth.isAuthenticated,
});

export default connect(mapStateToProps)(Base);
