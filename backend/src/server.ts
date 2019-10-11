import setupMongo from 'app/setup/mongoose';
import setupApp from 'app/setup/app';

(async (): Promise<void> => {
  await setupMongo();
  await setupApp();
})();
