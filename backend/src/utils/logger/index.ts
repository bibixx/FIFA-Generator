import {
  createLogger, LoggerOptions, transports, format,
} from 'winston';
import { StreamOptions } from 'morgan';

export const MORGAN_FORMAT = ':remote-addr - :remote-user ":method :url HTTP/:http-version" :status :res[content-length] ":referrer" ":user-agent"';

const loggerFormat = format.combine(
  format.timestamp({
    format: 'YYYY-MM-DD HH:mm:ss',
  }),
  format.printf((info): string => `[${info.timestamp}] ${info.level}: ${info.message}`),
);

const options: LoggerOptions = {
  transports: [
    new transports.Console({
      level: 'debug',
      format: format.combine(
        loggerFormat,
        format.colorize({ all: true }),
      ),
    }),
  ],
};

const logger = createLogger(options);

export class MorganStream implements StreamOptions {
  // eslint-disable-next-line class-methods-use-this
  public write(text: string): void {
    logger.info(text.trimRight());
  }
}

export default logger;
