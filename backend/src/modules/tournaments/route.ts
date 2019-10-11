import express from 'express';
import {
  createTournament,
  getTournaments,
  getTournamentById,
  deleteTournamentById,
  editTournamentById,
} from 'app/modules/tournaments/controller';

import isAdmin from 'app/permissions/middleware';

import asyncHandler from 'app/utils/asyncHandler';

const router = express.Router()
  .get('/', asyncHandler(getTournaments))
  .get('/:uuid', asyncHandler(getTournamentById))
  .post('/', asyncHandler(createTournament))
  .delete('/:uuid', isAdmin, asyncHandler(deleteTournamentById))
  .patch('/:uuid', isAdmin, asyncHandler(editTournamentById));

export default router;
