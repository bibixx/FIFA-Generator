const includeProperty = <T>(
  include: boolean,
  property: string,
  value: T,
): ({ [key: string]: T } | {}) => (
    include
      ? ({
        [property]: value,
      })
      : {}
  );

export default includeProperty;
