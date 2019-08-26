import express from 'express';
import morgan from 'morgan';
import errorHandler from 'errorhandler';

import { MORGAN_FORMAT } from './constants';
import { MorganStream } from './utils/logger';
import isProduction from './utils/isProduction';
import router from './routes';

import internalError500Controller from './modules/errors/internalError500/controller';
import notFoundError404Controller from './modules/errors/notFoundError404/controller';

const app = express();

app.use(morgan(MORGAN_FORMAT, { stream: new MorganStream() }));

app.use(router);

if (isProduction()) {
  app.use(internalError500Controller);
} else {
  app.use(errorHandler());
}

app.use(notFoundError404Controller);

export default app;
