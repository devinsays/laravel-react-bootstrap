import { applyMiddleware, createStore, compose } from 'redux';
import { persistStore } from 'redux-persist';
import ReduxThunk from 'redux-thunk';
import RootReducer from './reducers';

const store = createStore(RootReducer, compose(applyMiddleware(ReduxThunk)));

persistStore(store);

export default store;
