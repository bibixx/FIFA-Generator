import logger from './utils/logger';

import app from './app';

const PORT = process.env.PORT || 3000;

app.listen(3000, (): void => {
  logger.verbose(`App is listening on http://localhost:${PORT}`);
});
