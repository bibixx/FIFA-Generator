import express from 'express';
import morgan from 'morgan';
import errorHandler from 'errorhandler';

import { MorganStream } from './utils/logger';
import isProduction from './utils/isProduction';
import router from './routes';

import internalError500Controller from './modules/internalError500/controller';
import notFoundError404Controller from './modules/notFoundError404/controller';

const app = express();

app.use(morgan(':remote-addr - :remote-user ":method :url HTTP/:http-version" :status :res[content-length] ":referrer" ":user-agent"', { stream: new MorganStream() }));

app.use(router);

if (isProduction()) {
  app.use(internalError500Controller);
} else {
  app.use(errorHandler());
}

app.use(notFoundError404Controller);

export default app;
