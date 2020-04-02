import { combineReducers } from 'redux';
import Auth from './Auth';
import persistStore from './persistStore';

const RootReducer = combineReducers({ Auth, persistStore });

export default RootReducer;
