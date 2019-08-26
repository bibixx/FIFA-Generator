import { createConnection } from 'typeorm';
import logger from './utils/logger';

import app from './app';

const PORT = process.env.PORT || 3000;

(async (): Promise<void> => {
  logger.verbose('Started connecting to database');
  await createConnection();
  logger.verbose('Connected to database');

  app.listen(3000, (): void => {
    logger.verbose(`App is listening on http://localhost:${PORT}`);
  });
})();
