import express from 'express';

import tournamentsRouter from 'app/modules/tournaments/route';

const router = express.Router()
  .use('/tournaments', tournamentsRouter);

export default router;
