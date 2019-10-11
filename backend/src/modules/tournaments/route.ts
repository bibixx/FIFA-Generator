import express from 'express';
import {
  createTournament,
  getTournaments,
  getTournamentById,
  deleteTournamentById,
} from 'app/modules/tournaments/controller';

import asyncHandler from 'app/utils/asyncHandler';

const router = express.Router()
  .post('/', asyncHandler(createTournament))
  .get('/', asyncHandler(getTournaments))
  .get('/:uuid', asyncHandler(getTournamentById))
  .delete('/:uuid', asyncHandler(deleteTournamentById));

export default router;
