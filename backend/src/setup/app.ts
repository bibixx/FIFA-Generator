import express from 'express';
import morgan from 'morgan';
import bodyParser from 'body-parser';

import router from 'app/routes';
import errorHandler from 'app/utils/errorHandler';

import logger, { MORGAN_FORMAT, MorganStream } from 'app/utils/logger';

const { PORT } = process.env;

const setupApp = async (): Promise<void> => {
  const app = express();

  app.use(bodyParser.json());

  app.use(morgan(MORGAN_FORMAT, { stream: new MorganStream() }));

  app.use(router);

  app.listen(PORT, (): void => {
    logger.verbose(`Server listening on port ${PORT}`);
  });

  app.use(errorHandler);
};

export default setupApp;
