import express from 'express';
import router from 'app/routes';

const { PORT } = process.env;

const setupApp = async (): Promise<void> => {
  const app = express();

  app.use(router);

  app.listen(PORT, (): void => {
    console.log(`Server listening on port ${PORT}`);
  });
};

export default setupApp;
